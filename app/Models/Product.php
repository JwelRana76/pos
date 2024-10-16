<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public static $columns = [
        ['name' => 'image', 'data' => 'image'],
        ['name' => 'name', 'data' => 'name'],
        ['name' => 'code', 'data' => 'code'],
        ['name' => 'brand', 'data' => 'brand'],
        ['name' => 'category', 'data' => 'category'],
        ['name' => 'cost', 'data' => 'cost'],
        ['name' => 'price', 'data' => 'price'],
        ['name' => 'stock', 'data' => 'stock'],
        ['name' => 'action', 'data' => 'action'],
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function size()
    {
        return $this->belongsTo(Size::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    public function getStockAttribute()
    {
        $purchase = Purchase::join('product_purchases', 'purchases.id', 'product_purchases.purchase_id')
        ->where('product_purchases.product_id', $this->id)->sum('product_purchases.qty');
        $sale = Sale::join('product_sales', 'sales.id', 'product_sales.sale_id')
        ->where('product_sales.product_id', $this->id)->sum('product_sales.qty');
        // $purchase_return = PurchaseReturnProduct::where('product_id', $this->id)->sum('qty');
        // $sale_return = SaleReturnProduct::where('product_id', $this->id)->sum('qty');
        // return $purchase - $sale - $purchase_return + $sale_return;
        return $purchase - $sale;
    }
}
