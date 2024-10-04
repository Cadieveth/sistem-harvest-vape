<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sales extends Model
{
    protected $table = 'sales';

    public $timestamps = false;

    protected $fillable = [
        'kode_sales',
        'date_sales',
        'kode_barang',
        'nama_barang',
        'harga_barang',
        'jumlah_sales',
        'total_sales',
        'harga_potongan',
        'penjualan_bersih',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->generateKode();
            $model->syncWithInventory();
        });

        self::created(function ($model) {
            $model->createJournalEntries();
        });

        self::updating(function ($model) {
            if ($model->isDirty('jumlah_sales')) {
                $originalJumlahSales = $model->getOriginal('jumlah_sales');

                $inventory = Inventory::where('kode_barang', $model->kode_barang)->first();
                if ($inventory) {
                    $stok_lama = $inventory->jumlah_barang + $originalJumlahSales;
                    $stok_baru = $stok_lama - $model->jumlah_sales;
                    $inventory->update([
                        'jumlah_barang' => $stok_baru,
                    ]);
                }
            }
        });

    }

    protected function generateKode()
    {
        $prefix = '1924';

        $latest_sales = self::where('kode_sales', 'LIKE', $prefix . '%')
            ->orderBy('kode_sales', 'desc')
            ->first();

        if ($latest_sales) {
            $lastNumber = (int) substr($latest_sales->kode_sales, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $this->kode_sales = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    protected function syncWithInventory()
    {
        $inventory = Inventory::where('kode_barang', $this->kode_barang)->first();

        if ($inventory) {
            $stok_baru = $inventory->jumlah_barang - $this->jumlah_sales;
            $inventory->update([
                'jumlah_barang' => $stok_baru,
            ]);
        }
    }

    protected function createJournalEntries()
    {
        $total_sales = $this->total_sales;
        $nama_barang = $this->nama_barang;
        $kode_sales = $this->kode_sales;
        $date_sales = $this->date_sales;

        Journal::create([
            'tanggal_jurnal' => $date_sales,
            'debit_acc_id' => 3,
            'kredit_acc_id' => 10,
            'debit_jurnal' => $total_sales,
            'kredit_jurnal' => $total_sales,
            'ket_jurnal' => 'Penjualan ' . $nama_barang . ' (' . $kode_sales . ')',
        ]);

        Journal::create([
            'tanggal_jurnal' => $date_sales,
            'debit_acc_id' => 24,
            'kredit_acc_id' => 7,
            'debit_jurnal' => $total_sales / 1.4,
            'kredit_jurnal' => $total_sales / 1.4,
            'ket_jurnal' => 'Penjualan ' . $nama_barang . ' (' . $kode_sales . ')',
        ]);

        if ($this->harga_potongan > 0) {
            Journal::create([
                'tanggal_jurnal' => $date_sales,
                'debit_acc_id' => 15,
                'kredit_acc_id' => 3,
                'debit_jurnal' => $this->harga_potongan,
                'kredit_jurnal' => $this->harga_potongan,
                'ket_jurnal' => 'Potongan Penjualan ' . $nama_barang . ' (' . $kode_sales . ')',
            ]);
        }
    }
}
