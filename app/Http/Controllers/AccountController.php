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
        Account::findOrFail($id)->delete();
        return redirect()->route('account.index')->with('success', 'Account Deleted Successfully');
    }
    public function updateStatus(Request $request)
    {
        $item = Account::find($request->id);

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $item->is_default = $request->status;
        $item->save();

        $accounts = Account::where('id', '!=', $item->id)->get();
        foreach ($accounts as $key => $item) {
            if ($item->is_default == true) {
                Account::findOrFail($item->id)->update(['is_default' => false]);
            }
        }

        return response()->json(['message' => 'Status updated successfully']);
    }
}
