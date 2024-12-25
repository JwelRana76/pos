<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public static $columns = [
        ['name' => 'date', 'data' => 'date'],
        ['name' => 'voucher_no', 'data' => 'voucher_no'],
        ['name' => 'supplier', 'data' => 'supplier'],
        ['name' => 'grand_total', 'data' => 'grand_total'],
        ['name' => 'paid', 'data' => 'paid'],
        ['name' => 'due', 'data' => 'due'],
        ['name' => 'payment_status', 'data' => 'payment_status'],
        ['name' => 'action', 'data' => 'action'],
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function product_purchase()
    {
        return $this->hasMany(ProductPurchase::class);
    }
    public function getPaidAttribute()
    {
        $paid = PurchasePayment::where('purchase_id', $this->id)->sum('amount');
        return $paid;
    }
    public function getDueAttribute()
    {
        return $this->grand_total - $this->paid;
    }
    public function payments()
    {
        return $this->hasMany(PurchasePayment::class, 'purchase_id', 'id');
    }
}
