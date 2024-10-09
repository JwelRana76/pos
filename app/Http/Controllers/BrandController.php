<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Service\BrandService;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->baseService = new BrandService;
    }
    public function index()
    {
        $item = $this->baseService->Index();
        $columns = Brand::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.brand.index', compact('columns'));
    }

    public function store(Request $request)
    {
        if ($request->id == null) {
            $request->validate([
                'name' => 'required',
                'code' => 'required|unique:brands',
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('brand.index')->with($message);
    }
    public function brandstore(Request $request)
    {
        if ($request->id == null) {
            $request->validate([
                'name' => 'required',
                'code' => 'required|unique:brands'
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        $brands = Brand::all();
        return $brands;
    }
    function edit($id)
    {
        $brand = Brand::findOrFail($id);
        $item = $this->baseService->Index();
        $columns = Brand::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.brand.index', compact('columns', 'brand'));
    }
    function delete($id)
    {
        Brand::findOrFail($id)->delete();
        return redirect()->route('brand.index')->with('success', 'Brand Deleted Successfully');
    }
}
