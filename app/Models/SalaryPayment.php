<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryPayment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static $columns = [
        ['name' => 'month', 'data' => 'month'],
        ['name' => 'employee', 'data' => 'employee'],
        ['name' => 'monthly_salary', 'data' => 'monthly_salary'],
        ['name' => 'due_salary', 'data' => 'due_salary'],
        ['name' => 'advance_salary', 'data' => 'advance_salary'],
        ['name' => 'total_salary', 'data' => 'total_salary'],
        ['name' => 'paid', 'data' => 'amount'],
        ['name' => 'action', 'data' => 'action'],
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function month()
    {
        return $this->belongsTo(MonthlySalary::class, 'monthly_salary_id', 'id');
    }
}
