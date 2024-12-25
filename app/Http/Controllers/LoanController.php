<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Loan;
use App\Service\LoanService;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function __construct()
    {
        $this->baseService = new LoanService;
    }
    public function index()
    {

        if (!userHasPermission('loan-index')) {
            return view('404');
        }
        $item = $this->baseService->Index();
        $columns = Loan::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.loan.index', compact('columns'));
    }
    public function trash()
    {

        if (!userHasPermission('loan-advance')) {
            return view('404');
        }
        $item = $this->baseService->Trash();
        $columns = Loan::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.loan.trash', compact('columns'));
    }
    public function create()
    {
        if (!userHasPermission('loan-store')) {
            return view('404');
        }
        $accounts = Account::get();
        return view('pages.loan.create', compact('accounts'));
    }
    public function edit($id)
    {
        if (!userHasPermission('loan-update')) {
            return view('404');
        }
        $loan = Loan::findOrFail($id);
        return view('pages.loan.edit', compact('loan'));
    }

    public function store(Request $request)
    {
        if (!userHasPermission('loan-store')) {
            return view('404');
        }
        if ($request->id == null) {
            $request->validate([
                'amount' => 'required',
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('loan.index')->with($message);
    }
    public function update(Request $request, $id)
    {
        if (!userHasPermission('loan-update')) {
            return view('404');
        }
        if ($request->id == null) {
            $request->validate([
                'amount' => 'required',
                'contact' => 'required',
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->update($data, $id);
        return redirect()->route('loan.index')->with($message);
    }
    function delete($id)
    {
        if (!userHasPermission('loan-delete')) {
            return view('404');
        }
        Loan::findOrFail($id)->delete();
        return redirect()->route('loan.index')->with('success', 'Loan Deleted Successfully');
    }
    function restore($id)
    {
        if (!userHasPermission('loan-advance')) {
            return view('404');
        }
        $loan = Loan::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('loan.index')->with('success', 'Loan Restored Successfully');
    }
    function pdelete($id)
    {
        if (!userHasPermission('loan-advance')) {
            return view('404');
        }
        Loan::withTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('loan.trash')->with('success', 'Loan Permanently Deleted Successfully');
    }
}
