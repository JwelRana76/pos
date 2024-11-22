<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ["id"];

    public static $columns = [
        ['name' => 'date', 'data' => 'date'],
        ['name' => 'name', 'data' => 'name'],
        ['name' => 'contact', 'data' => 'contact'],
        ['name' => 'amount', 'data' => 'amount'],
        ['name' => 'interest_rate', 'data' => 'interest_rate'],
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
        return LoanReturn::where('loan_id', $this->id)->sum('amount');
    }
    function getBalanceAttribute()
    {
        $return = LoanReturn::where('loan_id', $this->id)->sum('amount');
        return $this->amount - $return;
    }
}
