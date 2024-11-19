<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AdvanceSalary;
use App\Models\Bank;
use App\Models\Employee;
use App\Models\MonthlySalary;
use App\Models\SalaryPayment;
use App\Service\SalaryPaymentService;
use Illuminate\Http\Request;

use function Pest\Laravel\json;

class SalaryPaymentController extends Controller
{
    public function __construct()
    {
        $this->baseService = new SalaryPaymentService;
    }
    public function index()
    {
        $item = $this->baseService->Index();
        $columns = SalaryPayment::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.salary_payment.index', compact('columns'));
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
        return view('pages.salary_payment.create', compact('employees', 'banks', 'accounts'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('salary-payment.index')->with($message);
    }
    function edit($id)
    {
        $salary = SalaryPayment::findOrFail($id);
        $employees = Employee::select('name', 'id', 'contact')->get();
        return view('pages.salary_payment.edit', compact('salary', 'employees'));
    }
    function update(Request $request, $id)
    {
        $data = $request->all();
        $message = $this->baseService->update($data, $id);
        return redirect()->route('salary-payment.index')->with($message);
    }
    function delete($id)
    {
        SalaryPayment::findOrFail($id)->delete();
        return redirect()->route('salary-payment.index')->with('success', 'Paid Salary Deleted Successfully');
    }

    function salary_details($month, $employee_id)
    {
        $monthly_salary = MonthlySalary::where('employee_id', $employee_id)->where('month', $month)->first();
        $advance_paid = AdvanceSalary::where('employee_id', $employee_id)->where('month', $month)->first()->amount ?? 0;
        $previous_advance_paid = AdvanceSalary::where('employee_id', $employee_id)->where('month', '<', $month)->sum('amount');
        $previous_salary = MonthlySalary::where('employee_id', $employee_id)->where('month', '<', $month)->sum('payable');
        $salary_paid = SalaryPayment::join('monthly_salaries', 'monthly_salaries.id', 'salary_payments.monthly_salary_id')
            ->where('monthly_salaries.employee_id', $employee_id)->where('monthly_salaries.month', '<', $month)->sum('salary_payments.amount');
        $due = $previous_salary - ($previous_advance_paid + $salary_paid);
        return response()->json([
            'monthly_salary_id' => $monthly_salary->id,
            'monthly_salary' => $monthly_salary->payable,
            'advance_salary' => $advance_paid,
            'due' => $due,
        ]);
    }
}
