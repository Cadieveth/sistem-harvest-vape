<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $table = 'journal';

    protected $fillable = [
        'tanggal_jurnal',
        'debit_acc_id',
        'kredit_acc_id',
        'debit_jurnal',
        'kredit_jurnal',
        'ket_jurnal',
    ];

    public $timestamps = false;

    public function debitAccount()
    {
        return $this->belongsTo(Account::class, 'debit_acc_id');
    }

    public function kreditAccount()
    {
        return $this->belongsTo(Account::class, 'kredit_acc_id');
    }
}
