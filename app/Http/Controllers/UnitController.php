<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Service\UnitService;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->baseService = new UnitService;
    }
    public function index()
    {
        $item = $this->baseService->Index();
        $columns = Unit::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.unit.index', compact('columns'));
    }

    public function store(Request $request)
    {
        if ($request->id == null) {
            $request->validate([
                'name' => 'required',
                'code' => 'required|unique:units',
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('unit.index')->with($message);
    }
    public function unitstore(Request $request)
    {
        if ($request->id == null) {
            $request->validate([
                'name' => 'required',
                'code' => 'required|unique:units'
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        $units = Unit::all();
        return $units;
    }
    function edit($id)
    {
        $unit = Unit::findOrFail($id);
        $item = $this->baseService->Index();
        $columns = Unit::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.unit.index', compact('columns', 'unit'));
    }
    function delete($id)
    {
        Unit::findOrFail($id)->delete();
        return redirect()->route('unit.index')->with('success', 'Unit Deleted Successfully');
    }
}
