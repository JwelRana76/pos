<?php

namespace App\Http\Controllers;

use App\Models\Size;
use App\Service\SizeService;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function __construct()
    {
        $this->baseService = new SizeService;
    }
    public function index()
    {
        $item = $this->baseService->Index();
        $columns = Size::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.size.index', compact('columns'));
    }

    public function store(Request $request)
    {
        if ($request->id == null) {
            $request->validate([
                'name' => 'required',
                'code' => 'required|unique:sizes',
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('size.index')->with($message);
    }
    public function sizestore(Request $request)
    {
        if ($request->id == null) {
            $request->validate([
                'name' => 'required',
                'code' => 'required|unique:sizes'
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        $sizes = Size::all();
        return $sizes;
    }
    function edit($id)
    {
        $size = Size::findOrFail($id);
        $item = $this->baseService->Index();
        $columns = Size::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.size.index', compact('columns', 'size'));
    }
    function delete($id)
    {
        Size::findOrFail($id)->delete();
        return redirect()->route('size.index')->with('success', 'Size Deleted Successfully');
    }
}
