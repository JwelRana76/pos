<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AdvanceSalary;
use App\Models\Bank;
use App\Models\Employee;
use App\Service\AdvanceSalaryService;
use Illuminate\Http\Request;

class AdvanceSalaryController extends Controller
{
    public function __construct()
    {
        $this->baseService = new AdvanceSalaryService;
    }
    public function index()
    {
        $item = $this->baseService->Index();
        $columns = AdvanceSalary::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.advance_salary.index', compact('columns'));
    }
    public function create()
    {
        $employees = Employee::select('name', 'id', 'contact')->get();
        $banks = Bank::select('bank_name as name', 'id', 'account_no')
            ->get()
            ->append('balance');
        $accounts = Account::select('name as name', 'id', 'account_no')
            ->get()
            ->append('balance');
        $advance_salarys = AdvanceSalary::get();
        return view('pages.advance_salary.create', compact('advance_salarys', 'employees', 'banks', 'accounts'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('advance-salary.index')->with($message);
    }
    function edit($id)
    {
        $salary = AdvanceSalary::findOrFail($id);
        $employees = Employee::select('name', 'id', 'contact')->get();
        $banks = Bank::select('bank_name as name', 'id', 'account_no')
            ->get()
            ->append('balance');
        $accounts = Account::select('name as name', 'id', 'account_no')
            ->get()
            ->append('balance');
        return view('pages.advance_salary.edit', compact('salary', 'employees', 'banks', 'accounts'));
    }
    function update(Request $request, $id)
    {
        $data = $request->all();
        $message = $this->baseService->update($data, $id);
        return redirect()->route('advance-salary.index')->with($message);
    }
    function delete($id)
    {
        AdvanceSalary::findOrFail($id)->delete();
        return redirect()->route('advance-salary.index')->with('success', 'Advance Salary Deleted Successfully');
    }
}
