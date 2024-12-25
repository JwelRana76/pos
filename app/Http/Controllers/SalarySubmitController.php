<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\MonthlySalary;
use App\Service\SalarySubmitService;
use Illuminate\Http\Request;

class SalarySubmitController extends Controller
{
    public function __construct()
    {
        $this->baseService = new SalarySubmitService;
    }
    public function index()
    {
        if (!userHasPermission('payrole-index')) {
            return view('404');
        }
        $item = $this->baseService->Index();
        $columns = MonthlySalary::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.salary_submit.index', compact('columns'));
    }
    public function create()
    {
        if (!userHasPermission('payrole-store')) {
            return view('404');
        }
        $employees = Employee::select('name', 'id', 'contact')->get();

        return view('pages.salary_submit.create', compact('employees'));
    }

    public function store(Request $request)
    {
        if (!userHasPermission('payrole-store')) {
            return view('404');
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('salary-submit.index')->with($message);
    }
    function edit($id)
    {
        if (!userHasPermission('payrole-update')) {
            return view('404');
        }
        $salary = MonthlySalary::findOrFail($id);
        $employees = Employee::select('name', 'id', 'contact')->get();
        return view('pages.Salary_submit.edit', compact('salary', 'employees'));
    }
    function update(Request $request, $id)
    {
        if (!userHasPermission('payrole-update')) {
            return view('404');
        }
        $data = $request->all();
        $message = $this->baseService->update($data, $id);
        return redirect()->route('salary-submit.index')->with($message);
    }
    function delete($id)
    {
        if (!userHasPermission('payrole-delete')) {
            return view('404');
        }
        MonthlySalary::findOrFail($id)->delete();
        return redirect()->route('salary-submit.index')->with('success', 'Monthly Salary Deleted Successfully');
    }
}
