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
        if (!userHasPermission('expense-index')) {
            return view('404');
        }
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
        if (!userHasPermission('expense-advance')) {
            return view('404');
        }
        $item = $this->baseService->Trash();
        $columns = Expense::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.expense.trash', compact('columns'));
    }

    public function store(Request $request)
    {
        if (!userHasPermission('expense-store')) {
            return view('404');
        }
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
        if (!userHasPermission('expense-update')) {
            return view('404');
        }
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
        if (!userHasPermission('expense-delete')) {
            return view('404');
        }
        Expense::findOrFail($id)->delete();
        return redirect()->route('expense.index')->with('success', 'Expense Deleted Successfully');
    }

    function restore($id)
    {
        if (!userHasPermission('expense-advance')) {
            return view('404');
        }
        Expense::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('expense.index')->with('success', 'Expense Restore Successfully');
    }
    function pdelete($id)
    {
        if (!userHasPermission('expense-advance')) {
            return view('404');
        }
        Expense::withTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('expense.trash')->with('success', 'Expense Permanently Deleted Successfully');
    }
    function receipt($id)
    {
        if (!userHasPermission('expense-advance')) {
            return view('404');
        }
        $expense = Expense::findOrFail($id);
        return view('pages.expense.receipt', compact('expense'));
    }
}
