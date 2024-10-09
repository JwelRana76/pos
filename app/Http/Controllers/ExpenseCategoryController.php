<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use App\Service\ExpenseCategoryService;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function __construct()
    {
        $this->baseService = new ExpenseCategoryService;
    }
    public function index()
    {
        $item = $this->baseService->Index();
        $columns = ExpenseCategory::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.expense_category.index', compact('columns'));
    }

    public function store(Request $request)
    {
        // return $request->all();
        if ($request->id == null) {
            $request->validate([
                'name' => 'required',
                'code' => 'required|unique:expense_categories'
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('expense-category.index')->with($message);
    }
    public function categprystore(Request $request)
    {
        // return $request->all();
        if ($request->id == null) {
            $request->validate([
                'name' => 'required',
                'code' => 'required|unique:expense_categories'
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        $categories = ExpenseCategory::all();
        return $categories;
    }
    function edit($id)
    {
        $category = ExpenseCategory::findOrFail($id);
        $item = $this->baseService->Index();
        $columns = ExpenseCategory::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.expense_category.index', compact('columns', 'category'));
    }
    function delete($id)
    {
        ExpenseCategory::findOrFail($id)->delete();
        return redirect()->route('expense-categoyr.index')->with('success', 'Expense Category Deleted Successfully');
    }
}
