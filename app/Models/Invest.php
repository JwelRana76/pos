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
        ['name' => 'entry_by', 'data' => 'entry_by'],
        ['name' => 'invest_to', 'data' => 'invest_to'],
        ['name' => 'note', 'data' => 'note'],
        ['name' => 'action', 'data' => 'action'],
    ];

    function user()
    {
        return $this->belongsTo(User::class);
    }
}
