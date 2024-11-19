<?php

namespace App\Service;

use App\Models\MonthlySalary;
use App\Models\MonthlySalaryDetail;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SalarySubmitService
{
    protected $model = MonthlySalary::class;
    protected $details = MonthlySalaryDetail::class;

    public function Index()
    {
        $data = $this->model::all();

        return DataTables::of($data)
            ->addColumn('month', function ($item) {
                $date = DateTime::createFromFormat('Y-m', $item->month);
                return $date->format('F-Y');
            })
            ->addColumn('employee', function ($item) {
                return $item->employee->name ?? 'N/A';
            })
            ->addColumn('fund', function ($item) {
                return $item->provident_fund ?? 'N/A';
            })
            ->addColumn('action', fn($item) => view('pages.salary_submit.action', compact('item'))->render())
            ->make(true);
    }
    public function create($data)
    {
        DB::beginTransaction();
        try {
            $findoldsubmit = $this->model::where('employee_id', $data['employees'])->where('month', $data['date'])->first();
            if ($findoldsubmit) {
                $message = ['warning' => 'This Month Salary Already Submitted of this Employee'];
            } else {
                $salary_data['month'] = $data['date'];
                $salary_data['total_salary'] = array_sum($data['amount']) - $data['amount']['crop'];
                $salary_data['crop'] = $data['amount']['crop'] ?? 0;
                $salary_data['provident_fund'] = $data['provident_fund'];
                $salary_data['payable'] = $data['total_pryable'];
                $salary_data['user_id'] = Auth::user()->id;
                $salary_data['employee_id'] = $data['employees'];
                $salary_data['note'] = $data['note'];
                $salary = $this->model::create($salary_data);
                foreach ($data['amount'] as $key => $amount) {
                    $details_data['monthly_salary_id'] = $salary->id;
                    $details_data['salary_particular_id'] = $key == 'crop' ? 0 : $key;
                    $details_data['amount'] = $amount ?? 0;
                    $this->details::create($details_data);
                }
                $message = ['success' => 'Monthly Salary Submitted Successfully'];
            }
            DB::commit();
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
    public function update($data, $id)
    {
        DB::beginTransaction();
        try {
            $salary = $this->model::findOrFail($id);
            $salary_data['month'] = $data['date'];
            $salary_data['total_salary'] = array_sum($data['amount']) - $data['amount']['crop'];
            $salary_data['crop'] = $data['amount']['crop'];
            $salary_data['provident_fund'] = $data['provident_fund'];
            $salary_data['payable'] = $data['total_pryable'];
            $salary_data['user_id'] = Auth::user()->id;
            $salary_data['employee_id'] = $data['employees'];
            $salary_data['note'] = $data['note'];
            $salary->update($salary_data);
            $salary->details()->delete();
            foreach ($data['amount'] as $key => $amount) {
                $details_data['monthly_salary_id'] = $salary->id;
                $details_data['salary_particular_id'] = $key == 'crop' ? 0 : $key;
                $details_data['amount'] = $amount;
                $this->details::create($details_data);
            }
            $message = ['success' => 'Monthly Salary Updated Successfully'];
            DB::commit();
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
}
