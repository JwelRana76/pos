<?php

namespace App\Service;

use App\Models\AdvanceSalary;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AdvanceSalaryService
{
    protected $model = AdvanceSalary::class;

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
            ->addColumn('action', fn($item) => view('pages.advance_salary.action', compact('item'))->render())
            ->make(true);
    }
    public function create($data)
    {
        DB::beginTransaction();
        try {
            $advance_salary['employee_id'] = $data['employees'];
            $advance_salary['user_id'] = Auth::user()->id;
            $advance_salary['payment_type'] = $data['payment_type'];
            $advance_salary['account_id'] = $data['payment_type'] == 1 ? $data['account'] : null;
            $advance_salary['bank_id'] = $data['payment_type'] == 0 ? $data['bank'] : null;
            $advance_salary['amount'] = $data['amount'];
            $advance_salary['month'] = $data['month'];
            $this->model::create($advance_salary);
            $message = ['success' => 'Advance Salary Paid Successfully'];
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
            $findother = $this->model::where('employee_id', $data['employees'])->where('month', $data['month'])->whereNot('id', $id)->first();

            if (!$findother) {
                $salary = $this->model::findOrFail($id);
                $advance_salary['employee_id'] = $data['employees'];
                $advance_salary['user_id'] = Auth::user()->id;
                $advance_salary['payment_type'] = $data['payment_type'];
                $advance_salary['payment_type'] = $data['payment_type'];
                $advance_salary['account_id'] = $data['payment_type'] == 1 ? $data['account'] : null;
                $advance_salary['bank_id'] = $data['payment_type'] == 0 ? $data['bank'] : null;
                $advance_salary['amount'] = $data['amount'];
                $advance_salary['month'] = $data['month'];
                $salary->update($advance_salary);
                $message = ['success' => 'Advance Salary Updated Successfully'];
                DB::commit();
            } else {
                $message = ['warning' => 'This Employee Already Take Advance Salary'];
            }
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
}
