<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\SalaryAssign;
use App\Service\SalaryAssignService;
use Illuminate\Http\Request;

class SalaryAssignController extends Controller
{
    public function __construct()
    {
        $this->baseService = new SalaryAssignService;
    }
    public function index()
    {
        $employees = Employee::get();
        return view('pages.salaryassign.index', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employees' => 'required',
        ]);
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('salary-assign.index', ['id' => 0])->with($message);
    }
    public function salarydetails($id)
    {
        $details = SalaryAssign::join('salary_particulars', 'salary_particulars.id', 'salary_assigns.salary_particular_id')
            ->where('salary_assigns.employee_id', $id)
            ->select('salary_assigns.amount as amount', 'salary_particulars.id as salary_particular_id', 'salary_particulars.is_provident')
            ->get();
        return $details;
    }
}
