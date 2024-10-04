<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceSheet extends Model
{
    protected $table = 'neraca_awal';

    protected $fillable = [
        'id_akun',
        'nominal',
        'modal',
    ];

    public $timestamps = false;

    public function account()
    {
        return $this->belongsTo(Account::class, 'id_akun');
    }
}
