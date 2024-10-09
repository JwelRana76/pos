<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Service\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->baseService = new CategoryService;
    }
    public function index()
    {
        $item = $this->baseService->Index();
        $columns = Category::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.category.index', compact('columns'));
    }

    public function store(Request $request)
    {
        if ($request->id == null) {
            $request->validate([
                'name' => 'required',
                'code' => 'required|unique:categories',
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('category.index')->with($message);
    }
    public function categorystore(Request $request)
    {
        if ($request->id == null) {
            $request->validate([
                'name' => 'required',
                'code' => 'required|unique:categories'
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        $categories = Category::all();
        return $categories;
    }
    function edit($id)
    {
        $category = Category::findOrFail($id);
        $item = $this->baseService->Index();
        $columns = Category::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.category.index', compact('columns', 'category'));
    }
    function delete($id)
    {
        Category::findOrFail($id)->delete();
        return redirect()->route('category.index')->with('success', 'Category Deleted Successfully');
    }
}
