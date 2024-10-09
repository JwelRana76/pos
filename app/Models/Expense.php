<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public static $columns = [
        ['name' => 'date', 'data' => 'date'],
        ['name' => 'category', 'data' => 'category'],
        ['name' => 'amount', 'data' => 'amount'],
        ['name' => 'entry_by', 'data' => 'entry_by'],
        ['name' => 'action', 'data' => 'action'],
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
