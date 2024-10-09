<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public static $columns = [
        ['name' => 'name', 'data' => 'name'],
        ['name' => 'account_no', 'data' => 'account_no'],
        ['name' => 'balance', 'data' => 'balance'],
        ['name' => 'action', 'data' => 'action'],
    ];

    public function getBalanceAttribute()
    {
        return 0;
    }
}
