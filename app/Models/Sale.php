<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $appends = ['paid'];
    public static $columns = [
        ['name' => 'date', 'data' => 'date'],
        ['name' => 'voucher_no', 'data' => 'voucher_no'],
        ['name' => 'customer', 'data' => 'customer'],
        ['name' => 'grand_total', 'data' => 'grand_total'],
        ['name' => 'paid', 'data' => 'paid'],
        ['name' => 'due', 'data' => 'due'],
        ['name' => 'payment_status', 'data' => 'payment_status'],
        ['name' => 'action', 'data' => 'action'],
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function product_sale()
    {
        return $this->hasMany(ProductSale::class);
    }
    public function getPaidAttribute()
    {
        $paid = SalePayment::where('sale_id', $this->id)->sum('amount');
        return $paid;
    }
    public function payments()
    {
        return $this->hasMany(SalePayment::class, 'sale_id', 'id');
    }
}
