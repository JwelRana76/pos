<?php

namespace App\Http\Controllers;

use App\Models\SalaryParticular;
use App\Service\SalaryParticularService;
use Illuminate\Http\Request;

class SalaryParticularController extends Controller
{
    public function __construct()
    {
        $this->baseService = new SalaryParticularService;
    }
    public function index()
    {
        $item = $this->baseService->Index();
        $columns = SalaryParticular::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.salaryparticular.index', compact('columns'));
    }

    public function store(Request $request)
    {
        if ($request->id == null) {
            $request->validate([
                'name' => 'required',
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('salary-particular.index')->with($message);
    }
    public function salaryparticularstore(Request $request)
    {
        if ($request->id == null) {
            $request->validate([
                'name' => 'required',
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        $salaryparticulars = SalaryParticular::all();
        return $salaryparticulars;
    }
    function edit($id)
    {
        $salaryparticular = salaryparticular::findOrFail($id);
        $item = $this->baseService->Index();
        $columns = salaryparticular::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.salaryparticular.index', compact('columns', 'salaryparticular'));
    }
    function delete($id)
    {
        SalaryParticular::findOrFail($id)->update(['is_active' => false]);
        return redirect()->route('salary-particular.index')->with('success', 'Salary Particular Deleted Successfully');
    }
}
