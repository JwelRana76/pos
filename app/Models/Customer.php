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

    public function scopeActive($query)
    {
        return $query->where('is_active', 1)->get();
    }
    public function district()
    {
        return $this->belongsTo(District::class);
    }
    public function getDueAttribute()
    {
        // $sale = Sale::where('customer_id', $this->id)->sum('grand_total');
        // $payment = SalePayment::where('customer_id', $this->id)->sum('amount');
        // $return = SaleReturn::where('customer_id', $this->id)->sum('grand_total');
        // $opening_due = Customer::find($this->id)->opening_due ?? 0;
        // return $sale - $payment + $opening_due - $return;
        return 0;
    }
}
