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
        if (!userHasPermission('employee-index')) {
            return view('404');
        }
        $item = $this->baseService->Index();
        $columns = Employee::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.employee.index', compact('columns'));
    }
    public function trash()
    {
        if (!userHasPermission('employee-advance')) {
            return view('404');
        }
        $item = $this->baseService->Trash();
        $columns = Employee::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.employee.trash', compact('columns'));
    }
    public function create()
    {
        if (!userHasPermission('employee-store')) {
            return view('404');
        }
        $districts = District::get();
        $divisions = Division::get();
        $roles = Role::all();
        return view('pages.employee.create', compact('districts', 'divisions', 'roles'));
    }
    public function store(Request $request)
    {
        if (!userHasPermission('employee-store')) {
            return view('404');
        }
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
        if (!userHasPermission('employee-update')) {
            return view('404');
        }
        $employee = Employee::findOrFail($id);
        $districts = District::get();
        $divisions = Division::get();
        $roles = Role::all();
        return view('pages.employee.edit', compact('employee', 'districts', 'divisions', 'roles'));
    }
    public function update(Request $request, $id)
    {
        if (!userHasPermission('employee-update')) {
            return view('404');
        }
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
        if (!userHasPermission('employee-delete')) {
            return view('404');
        }
        Employee::findOrFail($id)->delete();
        return redirect()->route('employee.index')->with('success', 'Employee Deleted Successfully');
    }
    function restore($id)
    {
        if (!userHasPermission('employee-advance')) {
            return view('404');
        }
        Employee::withTrashed()->findOrFail($id)->delete();
        return redirect()->route('employee.index')->with('success', 'Employee Restored Successfully');
    }
    function pdelete($id)
    {
        if (!userHasPermission('employee-advance')) {
            return view('404');
        }
        Employee::withTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('employee.index')->with('success', 'Employee Permanently Deleted Successfully');
    }
}
