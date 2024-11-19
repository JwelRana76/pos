<?php

namespace App\Service;

use App\Models\Employee;
use App\Models\SalaryAssign;
use Exception;
use Illuminate\Support\Facades\DB;

class SalaryAssignService
{
    protected $model = SalaryAssign::class;

    public function create($data)
    {
        DB::beginTransaction();
        try {
            $employee = Employee::findOrFail($data['employees']);
            $employee->salary_particular()->delete();
            foreach ($data['amount'] as $key => $amount) {
                $this->model::create([
                    'created_at' => $data['date'],
                    'employee_id' => $employee->id,
                    'salary_particular_id' => $key,
                    'amount' => $amount,
                ]);
            }
            $message = ['success' => 'Salary Assigned Successfully'];
            DB::commit();
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage(), $th->getLine());
        }
    }
}
