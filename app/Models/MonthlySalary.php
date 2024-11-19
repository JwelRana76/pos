<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlySalary extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static $columns = [
        ['name' => 'month', 'data' => 'month'],
        ['name' => 'employee', 'data' => 'employee'],
        ['name' => 'total_salary', 'data' => 'total_salary'],
        ['name' => 'crop', 'data' => 'crop'],
        ['name' => 'fund', 'data' => 'fund'],
        ['name' => 'payable', 'data' => 'payable'],
        ['name' => 'note', 'data' => 'note'],
        ['name' => 'action', 'data' => 'action'],
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function details()
    {
        return $this->hasMany(MonthlySalaryDetail::class);
    }
}
