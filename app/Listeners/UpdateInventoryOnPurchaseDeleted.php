<?php

namespace App\Listeners;

use App\Events\PurchaseDeleted;
use App\Models\Inventory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateInventoryOnPurchaseDeleted
{
    public function __construct()
    {
        //
    }

    public function handle(PurchaseDeleted $event)
    {
        $purchase = $event->purchase;

        // Mencari dan menghapus data di 'inventory' berdasarkan kode_barang
        Inventory::where('kode_barang', $purchase->kode_barang)->delete();
    }
}
