<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Service\AccountService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->baseService = new AccountService;
    }
    public function index()
    {
        $item = $this->baseService->Index();
        $columns = Account::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.account.index', compact('columns'));
    }

    public function store(Request $request)
    {
        if ($request->id == null) {
            $request->validate([
                'name' => 'required',
                'account_no' => 'required',
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('account.index')->with($message);
    }
    function edit($id)
    {
        $account = Account::findOrFail($id);
        $item = $this->baseService->Index();
        $columns = Account::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.account.index', compact('columns', 'account'));
    }
    function delete($id)
    {
        Account::findOrFail($id)->update(['is_active' => false]);
        return redirect()->route('account.index')->with('success', 'Account Deleted Successfully');
    }
}
