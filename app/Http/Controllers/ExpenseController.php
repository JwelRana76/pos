<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Service\ExpenseService;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->baseService = new ExpenseService;
    }
    public function index()
    {
        $item = $this->baseService->Index();
        $categories = ExpenseCategory::get();
        $columns = Expense::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.expense.index', compact('columns', 'categories'));
    }
    public function trash()
    {
        $item = $this->baseService->Trash();
        $columns = Expense::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.expense.trash', compact('columns'));
    }

    public function store(Request $request)
    {
        if ($request->id == null) {
            $request->validate([
                'amount' => 'required',
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('expense.index')->with($message);
    }
    function edit($id)
    {
        $expense = Expense::findOrFail($id);
        $item = $this->baseService->Index();
        $categories = ExpenseCategory::get();
        $columns = Expense::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.expense.index', compact('columns', 'expense', 'categories'));
    }
    function delete($id)
    {
        Expense::findOrFail($id)->delete();
        return redirect()->route('expense.index')->with('success', 'Expense Deleted Successfully');
    }

    function restore($id)
    {
        Expense::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('expense.index')->with('success', 'Expense Restore Successfully');
    }
    function pdelete($id)
    {
        Expense::withTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('expense.trash')->with('success', 'Expense Permanently Deleted Successfully');
    }
}
