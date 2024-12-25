<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransection extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static $columns = [
        ['name' => 'date', 'data' => 'date'],
        ['name' => 'account', 'data' => 'account'],
        ['name' => 'bank', 'data' => 'bank'],
        ['name' => 'type', 'data' => 'type'],
        ['name' => 'amount', 'data' => 'amount'],
        ['name' => 'action', 'data' => 'action'],
    ];

    function bank()
    {
        return $this->belongsTo(Bank::class);
    }
    function account()
    {
        return $this->belongsTo(Account::class);
    }
    function user()
    {
        return $this->belongsTo(User::class);
    }
}
