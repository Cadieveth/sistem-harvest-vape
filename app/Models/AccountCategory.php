<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountCategory extends Model
{
    protected $table = 'account_category';

    protected $fillable = [
        'category',
    ];

    public $timestamps = false;
}
