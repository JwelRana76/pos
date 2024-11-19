<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invest extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ["id"];

    public static $columns = [
        ['name' => 'date', 'data' => 'date'],
        ['name' => 'amount', 'data' => 'amount'],
        ['name' => 'return', 'data' => 'return'],
        ['name' => 'note', 'data' => 'note'],
        ['name' => 'action', 'data' => 'action'],
    ];

    function user()
    {
        return $this->belongsTo(User::class);
    }
    function getReturnAttribute()
    {
        return InvestReturn::where('invest_id', $this->id)->sum('amount');
    }
    function getBalanceAttribute()
    {
        $paid = $this->amount;
        $return = $this->return;
        return $paid - $return;
    }
}
