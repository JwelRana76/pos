<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\IncomeCategory;
use App\Service\IncomeService;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function __construct()
    {
        $this->baseService = new IncomeService;
    }
    public function index()
    {
        if (!userHasPermission('income-index')) {
            return view('404');
        }
        $item = $this->baseService->Index();
        $categories = IncomeCategory::get();
        $columns = Income::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.income.index', compact('columns', 'categories'));
    }
    public function trash()
    {
        if (!userHasPermission('income-advance')) {
            return view('404');
        }

        $item = $this->baseService->Trash();
        $columns = Income::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.income.trash', compact('columns'));
    }

    public function store(Request $request)
    {
        if (!userHasPermission('income-store')) {
            return view('404');
        }
        if ($request->id == null) {
            $request->validate([
                'amount' => 'required',
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('income.index')->with($message);
    }
    function edit($id)
    {
        if (!userHasPermission('income-update')) {
            return view('404');
        }
        $income = Income::findOrFail($id);
        $item = $this->baseService->Index();
        $categories = IncomeCategory::get();
        $columns = Income::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.income.index', compact('columns', 'income', 'categories'));
    }
    function delete($id)
    {
        if (!userHasPermission('income-delete')) {
            return view('404');
        }
        Income::findOrFail($id)->delete();
        return redirect()->route('income.index')->with('success', 'Income Deleted Successfully');
    }
    function restore($id)
    {
        if (!userHasPermission('income-advance')) {
            return view('404');
        }
        $income = Income::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('income.index')->with('success', 'Income Restored Successfully');
    }
    function pdelete($id)
    {
        if (!userHasPermission('income-advance')) {
            return view('404');
        }
        Income::withTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('income.trash')->with('success', 'Income Permanently Deleted Successfully');
    }
}
