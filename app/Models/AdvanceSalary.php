<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvanceSalary extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static $columns = [
        ['name' => 'month', 'data' => 'month'],
        ['name' => 'employee', 'data' => 'employee'],
        ['name' => 'amount', 'data' => 'amount'],
        ['name' => 'action', 'data' => 'action'],
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
