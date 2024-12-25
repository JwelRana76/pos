<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public static $columns = [
        ['name' => 'image', 'data' => 'image'],
        ['name' => 'name', 'data' => 'name'],
        ['name' => 'contact', 'data' => 'contact'],
        ['name' => 'district', 'data' => 'district'],
        ['name' => 'due', 'data' => 'due'],
        ['name' => 'action', 'data' => 'action'],
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
    public function getDueAttribute()
    {
        $sale = Sale::where('customer_id', $this->id)->sum('grand_total');
        $payment = SalePayment::where('customer_id', $this->id)->sum('amount');
        $opening_due = $this->opening_due;
        return $sale - $payment + $opening_due;
    }
    function getOpeningBalanceAttribute()
    {
        return $this->opening_due - $this->opening_due_paid;
    }
}
