<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
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
        // $purchase = Purchase::where('supplier_id', $this->id)->sum('grand_total') ?? 0;
        // $payment = PurchasePyament::where('supplier_id', $this->id)->sum('amount') ?? 0;
        $opening_due = Supplier::find($this->id)->opening_due ?? 0;
        // $return = PurchaseReturn::where('supplier_id', $this->id)->sum('grand_total');

        // return $purchase - $payment + $opening_due - $return;
        return $opening_due;
    }
}