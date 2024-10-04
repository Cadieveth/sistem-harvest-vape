<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendor';

    protected $fillable = [
        'kode_vendor',
        'nama_vendor',
        'kontak_vendor',
        'alamat_vendor',
    ];

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->generateKode();
        });
    }

    protected function generateKode()
    {
        $prefix = '9422';

        $latest_vendor = self::where('kode_vendor', 'LIKE', $prefix . '%')
            ->orderBy('kode_vendor', 'desc')
            ->first();

        if ($latest_vendor) {
            $lastNumber = (int) substr($latest_vendor->kode_vendor, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $this->kode_vendor = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}
