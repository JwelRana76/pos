<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Service\DivisionService;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function __construct()
    {
        $this->baseService = new DivisionService;
    }
    public function index()
    {
        $item = $this->baseService->Index();
        $columns = Division::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.division.index', compact('columns'));
    }

    public function store(Request $request)
    {
        // return $request->all();
        if ($request->id == null) {
            $request->validate([
                'name' => 'required',
                'code' => 'required|unique:divisions'
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('division.index')->with($message);
    }
    public function divisionstore(Request $request)
    {
        // return $request->all();
        if ($request->id == null) {
            $request->validate([
                'name' => 'required',
                'code' => 'required|unique:divisions'
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        $divisions = Division::all();
        return $divisions;
    }
    function edit($id)
    {
        $division = Division::findOrFail($id);
        $item = $this->baseService->Index();
        $columns = Division::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.division.index', compact('columns', 'division'));
    }
    function delete($id)
    {
        Division::findOrFail($id)->delete();
        return redirect()->route('division.index')->with('success', 'Division Deleted Successfully');
    }
}
