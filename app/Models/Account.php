<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'account';

    protected $fillable = [
        'kode_akun',
        'nama_akun',
        'category_id',
    ];

    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(AccountCategory::class, 'category_id');
    }
}
