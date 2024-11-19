<?php

namespace App\Service;

use App\Models\SalaryPayment;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SalaryPaymentService
{
    protected $model = SalaryPayment::class;

    public function Index()
    {
        $data = $this->model::with('month')->get();

        return DataTables::of($data)
            ->addColumn('month', function ($item) {
                $date = DateTime::createFromFormat('Y-m', $item->month->month);
                return $date->format('F-Y');
            })
            ->addColumn('monthly_salary', function ($item) {
                return $item->month->payable;
            })
            ->addColumn('advance_salary', function ($item) {
                return $item->advance;
            })
            ->addColumn('employee', function ($item) {
                return $item->employee->name ?? 'N/A';
            })
            ->addColumn('fund', function ($item) {
                return $item->provident_fund ?? 'N/A';
            })
            ->addColumn('action', fn($item) => view('pages.salary_payment.action', compact('item'))->render())
            ->make(true);
    }
    public function create($data)
    {
        DB::beginTransaction();
        try {
            $already_paid = SalaryPayment::join('monthly_salaries', 'monthly_salaries.id', 'salary_payments.monthly_salary_id')
                ->where('monthly_salaries.employee_id', $data['employees'])->where('monthly_salaries.month', $data['month'])->first();
            if ($already_paid) {
                $message = ['success' => 'Monthly Salary Already Submitted of this Employee or Month'];
            } else {
                $salary['user_id'] = Auth::user()->id;
                $salary['monthly_salary_id'] = $data['monthly_salary_id'];
                $salary['employee_id'] = $data['employees'];
                $salary['bank_id'] = $data['payment_type'] == 1 ? $data['account'] : $data['bank'];
                $salary['due_salary'] = $data['due_salary'];
                $salary['advance'] = $data['advance_paid'];
                $salary['total_salary'] = $data['total_salary'];
                $salary['amount'] = $data['paid'];
                $salary['payment_type'] = $data['payment_type'];

                $this->model::create($salary);
                $message = ['success' => 'Monthly Salary Submitted Successfully'];
            }
            DB::commit();
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
}
