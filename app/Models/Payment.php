<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment';

    public $timestamps = false;

    protected $fillable = [
        'kode_payment',
        'tanggal_payment',
        'ket_payment',
        'keterangan',
        'cost_payment',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->generateKode();
        });

        self::updating(function ($model) {
            if ($model->isDirty('ket_payment')) {
                $model->generateKode();
            }
        });
    }

    protected function generateKode()
    {
        $prefix = '';

        switch ($this->ket_payment) {
            case 'Listrik, Air, dan Telepon':
                $prefix = '6101';
                break;
            case 'Sewa':
                $prefix = '6301';
                break;
            case 'Pajak':
                $prefix = '6501';
                break;
            case 'Asuransi':
                $prefix = '6601';
                break;
            case 'Pemasaran':
                $prefix = '6701';
                break;
            case 'Bunga':
                $prefix = '6801';
                break;
            case 'Depresiasi':
                $prefix = '6901';
                break;
            case 'Pengiriman':
                $prefix = '5102';
                break;
            default:
                $prefix = '6201'; // Default for Gaji Karyawan
        }

        $latest = self::where('kode_payment', 'LIKE', $prefix . '%')
            ->orderBy('kode_payment', 'desc')
            ->first();

        if ($latest) {
            $lastNumber = (int) substr($latest->kode_payment, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $this->kode_payment = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}
