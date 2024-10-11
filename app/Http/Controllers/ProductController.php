<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use App\Models\Unit;
use App\Service\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->baseService = new ProductService;
    }
    public function index()
    {
        $item = $this->baseService->Index();
        $columns = Product::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.product.index', compact('columns'));
    }
    public function trash()
    {
        $item = $this->baseService->Trash();
        $columns = Product::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.product.trash', compact('columns'));
    }
    public function create()
    {
        $categories = Category::get();
        $brands = Brand::get();
        $sizes = Size::get();
        $units = Unit::get();
        return view('pages.product.create', compact('categories', 'brands', 'sizes', 'units'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'cost' => 'required',
        ]);
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('product.index')->with($message);
    }
    function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::get();
        $brands = Brand::get();
        $sizes = Size::get();
        $units = Unit::get();
        return view('pages.product.edit', compact('product', 'categories', 'brands', 'sizes', 'units'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'cost' => 'required',
        ]);
        $data = $request->all();
        $message = $this->baseService->update($data, $id);
        return redirect()->route('product.index')->with($message);
    }
    function delete($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->route('product.index')->with('success', 'Product Deleted Successfully');
    }
    function restore($id)
    {
        Product::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('product.index')->with('success', 'Product Restored Successfully');
    }
    function pdelete($id)
    {
        Product::withTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('product.trash')->with('success', 'Product Permanently Deleted Successfully');
    }

    function Import(Request $request)
    {

        $request->validate([
            'product_file' => 'required|file|mimes:csv,txt',
        ]);
        $message = $this->baseService->Import($request->all());
        return redirect()->route('product.index')->with($message);
    }
}
