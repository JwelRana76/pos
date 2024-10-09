<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Service\BankService;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function __construct()
    {
        $this->baseService = new BankService;
    }
    public function index()
    {
        $item = $this->baseService->Index();
        $columns = Bank::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.bank.index', compact('columns'));
    }

    public function store(Request $request)
    {
        if ($request->id == null) {
            $request->validate([
                'holder_name' => 'required',
                'account_no' => 'required',
                'bank_name' => 'required',
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('bank.index')->with($message);
    }
    function edit($id)
    {
        $bank = Bank::findOrFail($id);
        $item = $this->baseService->Index();
        $columns = Bank::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.bank.index', compact('columns', 'bank'));
    }
    function delete($id)
    {
        Bank::findOrFail($id)->delete();
        return redirect()->route('bank.index')->with('success', 'Bank Deleted Successfully');
    }
}