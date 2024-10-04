<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'purchase';

    public $timestamps = false;

    protected $fillable = [
        'kode_purchase',
        'kode_barang',
        'nama_barang',
        'jumlah_barang',
        'harga_barang',
        'harga_pokok',
        'total_purchase',
        'ket_purchase',
        'tanggal_pembelian',
        'biaya_kirim',
        'vendor_id',
        'batch',
    ];

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'kode_barang', 'kode_barang');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->generateKodePurchase();
            $model->generateBatch();
            $model->calculateHargaPokok();
            $model->calculateTotalPurchase();
            $model->syncWithInventory($model->jumlah_barang);
        });

        self::updating(function ($model) {
            // Check if keterangan is being updated
            if ($model->isDirty('ket_purchase')) {
                $model->generateKodePurchase();
            }

            // Always recalculate harga_pokok and total_purchase
            $model->calculateHargaPokok();
            $model->calculateTotalPurchase();

            // Update inventory if jumlah_barang or harga_barang is being updated
            if ($model->isDirty('jumlah_barang') || $model->isDirty('harga_barang')) {
                // Get the original jumlah_barang before update
                $originalJumlahBarang = $model->getOriginal('jumlah_barang');

                // Calculate the difference in quantity
                $difference = $model->jumlah_barang - $originalJumlahBarang;

                // Sync with inventory by adjusting stock
                $model->syncWithInventory($difference);
            }
        });
    }

    protected function generateKodePurchase()
    {
        $prefix = '';

        switch ($this->ket_purchase) {
            case 'Peralatan':
                $prefix = '1625';
                break;
            case 'Perlengkapan':
                $prefix = '1626';
                break;
            default:
                $prefix = '1624'; // Default for Persediaan
        }

        $latestPurchase = self::where('kode_purchase', 'LIKE', $prefix . '%')
            ->orderBy('kode_purchase', 'desc')
            ->first();

        if ($latestPurchase) {
            $lastNumber = (int) substr($latestPurchase->kode_purchase, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $this->kode_purchase = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    protected function generateBatch()
    {
        // Cek pada tabel Inventory berdasarkan kode_barang dan nama_barang
        $latestInventory = Inventory::where('kode_barang', $this->kode_barang)
            ->where('nama_barang', $this->nama_barang)
            ->orderBy('batch', 'desc')
            ->first();

        if ($latestInventory) {
            // Jika terdapat data dengan kode_barang dan nama_barang yang sama, tambahkan 1 ke batch
            $this->batch = $latestInventory->batch + 1;
        } else {
            // Jika tidak terdapat data yang sama, mulai batch dari 1
            $this->batch = 1;
        }
    }

    public function calculateHargaPokok()
    {
        if ($this->biaya_kirim > 0) {
            $this->harga_pokok = ($this->biaya_kirim / $this->jumlah_barang) + $this->harga_barang;
        } else {
            $this->harga_pokok = $this->harga_barang;
        }
    }

    public function calculateTotalPurchase()
    {
        $this->total_purchase = $this->jumlah_barang * $this->harga_pokok;
    }

    protected function syncWithInventory($difference)
    {
        // Menghitung harga_barang berdasarkan biaya_kirim dan jumlah_barang
        $calculatedHargaBarang = ($this->biaya_kirim / $this->jumlah_barang) + $this->harga_barang;

        $inventory = Inventory::where('kode_barang', $this->kode_barang)
        ->where('nama_barang', $this->nama_barang)
        ->where('batch', $this->batch)
        ->first();

        if ($inventory) {
            $stok_baru = $inventory->jumlah_barang + $difference;
            $inventory->update([
                'nama_barang' => $this->nama_barang,
                'jumlah_barang' => $stok_baru,
                'harga_barang' => $this->harga_pokok, // Update harga_barang dengan harga_pokok
            ]);
        } else {
            Inventory::create([
                'kode_barang' => $this->kode_barang,
                'nama_barang' => $this->nama_barang,
                'jumlah_barang' => $this->jumlah_barang,
                'harga_barang' => $this->harga_pokok,
                'kode_purchase' => $this->kode_purchase,
                'batch' => $this->batch,
            ]);
        }
    }
}
