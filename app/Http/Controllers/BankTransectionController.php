<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Bank;
use App\Models\BankTransection;
use App\Service\BankTransectionService;
use Illuminate\Http\Request;

class BankTransectionController extends Controller
{
    public function __construct()
    {
        $this->baseService = new BankTransectionService;
    }
    public function index()
    {

        if (!userHasPermission('loan-index')) {
            return view('404');
        }
        $item = $this->baseService->Index();
        $columns = BankTransection::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.bank_transection.index', compact('columns'));
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
        $banks = Bank::select('bank_name as name', 'id', 'account_no')
            ->get()
            ->append('balance');
        $accounts = Account::select('name as name', 'id', 'account_no')
            ->get()
            ->append('balance');
        return view('pages.bank_transection.create', compact('accounts', 'banks'));
    }
    public function edit($id)
    {
        if (!userHasPermission('loan-update')) {
            return view('404');
        }
        $banks = Bank::select('bank_name as name', 'id', 'account_no')
            ->get()
            ->append('balance');
        $accounts = Account::select('name as name', 'id', 'account_no')
            ->get()
            ->append('balance');
        $transection = BankTransection::findOrFail($id);
        return view('pages.bank_transection.edit', compact('transection', 'banks', 'accounts'));
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
        return redirect()->route('bank_transection.index')->with($message);
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
        return redirect()->route('bank_transection.index')->with($message);
    }
    function delete($id)
    {
        if (!userHasPermission('loan-delete')) {
            return view('404');
        }
        BankTransection::findOrFail($id)->delete();
        return redirect()->route('bank_transection.index')->with('success', 'Bank Transection Deleted Successfully');
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
