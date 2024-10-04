<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BalanceSheet;

class Detail extends Model
{
    protected $table = 'detail';

    public $timestamps = false;

    protected $fillable = [
        'batch',
        'kode_barang',
        'kode_data',
        'nama_barang',
        'jumlah_barang',
        'harga_barang',
        'ket_barang',
    ];

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'kode_barang', 'kode_barang');
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->generateKodeData();
            $model->generateBatch();
            $model->syncWithInventory($model->jumlah_barang);
        });

        self::created(function ($model) {
            $model->syncWithBalanceSheet();
        });

        self::updating(function ($model) {
            if ($model->isDirty('ket_barang')) {
                $model->generateKodeData();
            }

            if ($model->isDirty('jumlah_barang') || $model->isDirty('harga_barang')) {
                $originalJumlahBarang = $model->getOriginal('jumlah_barang');

                $difference = $model->jumlah_barang - $originalJumlahBarang;

                $model->syncWithInventory($difference);
            }
        });

        self::updated(function ($model) {
            $model->syncWithBalanceSheet(); // Sync after update completes
        });

        self::deleted(function ($model) {
            $model->syncWithBalanceSheet(); // Sync with Balance Sheet on delete
        });
    }

    protected function syncWithBalanceSheet()
    {
        $categories = [
            '1621' => 7,   // Persediaan
            '1622' => 13,  // Peralatan
            '1623' => 14,  // Perlengkapan
        ];

        $prefix = substr($this->kode_data, 0, 4);
        if (!isset($categories[$prefix])) {
            return;
        }

        $id_akun = $categories[$prefix];

        $totalNominal = self::where('kode_data', 'LIKE', $prefix . '%')
            ->get()
            ->sum(function ($detail) {
                return $detail->jumlah_barang * $detail->harga_barang;
            });

        BalanceSheet::updateOrCreate(
            ['id_akun' => $id_akun],
            [
                'nominal' => $totalNominal,
                'modal' => $totalNominal,
            ]
        );
    }

    protected function generateKodeData()
    {
        $prefix = '';

        switch ($this->ket_barang) {
            case 'Peralatan':
                $prefix = '1622';
                break;
            case 'Perlengkapan':
                $prefix = '1623';
                break;
            default:
                $prefix = '1621'; // Default for Persediaan
        }

        $latestData = self::where('kode_data', 'LIKE', $prefix . '%')
            ->orderBy('kode_data', 'desc')
            ->first();

        if ($latestData) {
            $lastNumber = (int) substr($latestData->kode_data, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $this->kode_data = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    protected function generateBatch()
    {
        $latestInventory = Inventory::where('kode_barang', $this->kode_barang)
            ->where('nama_barang', $this->nama_barang)
            ->orderBy('batch', 'desc')
            ->first();

        if ($latestInventory) {
            $this->batch = $latestInventory->batch + 1;
        } else {
            $this->batch = 1;
        }
    }

    protected function syncWithInventory($difference)
    {
        $inventory = Inventory::where('kode_barang', $this->kode_barang)
        ->where('nama_barang', $this->nama_barang)
        ->where('batch', $this->batch)
        ->first();

        if ($inventory) {
            $stok_baru = $inventory->jumlah_barang + $difference;
            $inventory->update([
                'nama_barang' => $this->nama_barang,
                'jumlah_barang' => $stok_baru,
                'harga_barang' => $this->harga_barang, // Update harga_barang dengan harga_pokok
            ]);
        } else {
            Inventory::create([
                'kode_barang' => $this->kode_barang,
                'nama_barang' => $this->nama_barang,
                'jumlah_barang' => $this->jumlah_barang,
                'harga_barang' => $this->harga_barang,
                'kode_purchase' => $this->kode_data,
                'batch' => $this->batch,
            ]);
        }
    }
}
