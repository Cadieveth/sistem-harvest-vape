<?php

namespace App\Listeners;

use App\Events\PurchaseCreated;
use App\Models\Inventory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateInventoryOnPurchaseCreated
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PurchaseCreated $event): void
    {
        $purchase = $event->purchase;

        // Mencari data di 'inventory' dengan kode_barang yang sesuai
        $inventoryItem = Inventory::where('kode_barang', $purchase->kode_barang)->first();

        if (!$inventoryItem) {
            // Jika tidak ada, buat data baru di 'inventory'
            Inventory::create([
                'kode_barang' => $purchase->kode_barang,
                'nama_barang' => $purchase->nama_barang,
                'jumlah_barang' => $purchase->jumlah_barang,
                'harga_barang' => $purchase->harga_barang,
            ]);
        } else {
            // Jika sudah ada, update data di 'inventory'
            $inventoryItem->update([
                'nama_barang' => $purchase->nama_barang,
                'jumlah_barang' => $purchase->jumlah_barang,
                'harga_barang' => $purchase->harga_barang,
            ]);
        }
    }
}
