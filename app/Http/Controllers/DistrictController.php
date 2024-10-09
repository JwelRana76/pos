<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Division;
use App\Service\DistrictService;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function __construct()
    {
        $this->baseService = new DistrictService;
    }
    public function index()
    {
        $item = $this->baseService->Index();
        $columns = District::$columns;
        $divisions = Division::get();
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.district.index', compact('columns', 'divisions'));
    }

    public function store(Request $request)
    {
        // return $request->all();
        if ($request->id == null) {
            $request->validate([
                'name' => 'required',
                'code' => 'required|unique:districts'
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('district.index')->with($message);
    }
    public function districtstore(Request $request)
    {
        // return $request->all();
        if ($request->id == null) {
            $request->validate([
                'name' => 'required',
                'code' => 'required|unique:districts'
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        $districts = District::all();
        return $districts;
    }
    function edit($id)
    {
        $district = District::findOrFail($id);
        $item = $this->baseService->Index();
        $columns = District::$columns;
        $divisions = Division::active();
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.district.index', compact('columns', 'district', 'divisions'));
    }
    function delete($id)
    {
        District::findOrFail($id)->delete();
        return redirect()->route('district.index')->with('success', 'District Deleted Successfully');
    }
}
