<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public static $columns = [
        ['name' => 'holder_name', 'data' => 'holder_name'],
        ['name' => 'account_no', 'data' => 'account_no'],
        ['name' => 'bank_name', 'data' => 'bank_name'],
        ['name' => 'balance', 'data' => 'balance'],
        ['name' => 'action', 'data' => 'action'],
    ];
    public function getBalanceAttribute()
    {
        return 1000;
    }
}
