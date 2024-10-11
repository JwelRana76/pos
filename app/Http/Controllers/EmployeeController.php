<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Division;
use App\Models\Employee;
use App\Models\Role;
use App\Service\EmployeeService;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->baseService = new EmployeeService;
    }
    public function index()
    {
        $item = $this->baseService->Index();
        $columns = Employee::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.employee.index', compact('columns'));
    }
    public function trash()
    {
        $item = $this->baseService->Trash();
        $columns = Employee::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.employee.trash', compact('columns'));
    }
    public function create()
    {
        $districts = District::get();
        $divisions = Division::get();
        $roles = Role::all();
        return view('pages.employee.create', compact('districts', 'divisions', 'roles'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'contact' => 'required',
            'district' => 'required',
        ]);
        if ($request->is_user == 'on') {
            $request->validate([
                'user_name' => 'required',
                'password' => 'required',
                'cpassword' => 'required|same:password',
                'role' => 'required',
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('employee.index')->with($message);
    }
    function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $districts = District::get();
        $divisions = Division::get();
        $roles = Role::all();
        return view('pages.employee.edit', compact('employee', 'districts', 'divisions', 'roles'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'contact' => 'required',
            'district' => 'required',
        ]);
        if ($request->is_user == 'on') {
            $request->validate([
                'user_name' => 'required',
                'password' => 'required',
                'cpassword' => 'required|same:password',
                'role' => 'required',
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->update($data, $id);
        return redirect()->route('employee.index')->with($message);
    }
    function delete($id)
    {
        Employee::findOrFail($id)->delete();
        return redirect()->route('employee.index')->with('success', 'Employee Deleted Successfully');
    }
    function restore($id)
    {
        Employee::withTrashed()->findOrFail($id)->delete();
        return redirect()->route('employee.index')->with('success', 'Employee Restored Successfully');
    }
    function pdelete($id)
    {
        Employee::withTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('employee.index')->with('success', 'Employee Permanently Deleted Successfully');
    }
}
