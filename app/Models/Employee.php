<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public static $columns = [
        ['name' => 'image', 'data' => 'image'],
        ['name' => 'name', 'data' => 'name'],
        ['name' => 'contact', 'data' => 'contact'],
        ['name' => 'district', 'data' => 'district'],
        ['name' => 'action', 'data' => 'action'],
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function salary_particular()
    {
        return $this->hasMany(SalaryAssign::class, 'employee_id', 'id');
    }
}
