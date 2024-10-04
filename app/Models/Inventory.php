<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;
use App\Models\Detail;

class Inventory extends Model
{
    protected $table = 'inventory';

    public $timestamps = false;

    protected $fillable = ['kode_barang', 'nama_barang', 'jumlah_barang', 'harga_barang', 'kode_purchase', 'batch'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'kode_barang', 'kode_barang');
    }

    public function detail()
    {
        return $this->belongsTo(Detail::class, 'kode_barang', 'kode_barang');
    }

    public function scopeHasPurchase($query, $kodePurchase)
    {
        return $query->whereHas('purchase', function ($q) use ($kodePurchase) {
            $q->where('kode_purchase', 'LIKE', $kodePurchase . '%');
        });
    }

    public function scopeHasDetail($query, $kodeData)
    {
        return $query->whereHas('detail', function ($q) use ($kodeData) {
            $q->where('kode_data', 'LIKE', $kodeData . '%');
        });
    }
}
