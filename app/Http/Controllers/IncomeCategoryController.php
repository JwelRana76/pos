<?php

namespace App\Http\Controllers;

use App\Models\IncomeCategory;
use App\Service\IncomeCategoryService;
use Illuminate\Http\Request;

class IncomeCategoryController extends Controller
{
    public function __construct()
    {
        $this->baseService = new IncomeCategoryService;
    }
    public function index()
    {
        $item = $this->baseService->Index();
        $columns = IncomeCategory::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.income_category.index', compact('columns'));
    }
    public function trash()
    {
        $item = $this->baseService->Trash();
        $columns = IncomeCategory::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.income_category.trash', compact('columns'));
    }

    public function store(Request $request)
    {
        // return $request->all();
        if ($request->id == null) {
            $request->validate([
                'name' => 'required',
                'code' => 'required|unique:income_categories'
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('income-category.index')->with($message);
    }
    public function categprystore(Request $request)
    {
        // return $request->all();
        if ($request->id == null) {
            $request->validate([
                'name' => 'required',
                'code' => 'required|unique:income_categories'
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        $categories = IncomeCategory::all();
        return $categories;
    }
    function edit($id)
    {
        $category = IncomeCategory::findOrFail($id);
        $item = $this->baseService->Index();
        $columns = IncomeCategory::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.income_category.index', compact('columns', 'category'));
    }
    function delete($id)
    {
        IncomeCategory::findOrFail($id)->delete();
        return redirect()->route('income-category.index')->with('success', 'Income Category Deleted Successfully');
    }
    function restore($id)
    {
        IncomeCategory::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('income-category.index')->with('success', 'Income Category Restored Successfully');
    }
    function pdelete($id)
    {
        IncomeCategory::withTrashed()->find($id)->forceDelete();
        return redirect()->route('income-category.trash')->with('success', 'Income Category Permanently Deleted Successfully');
    }
}
