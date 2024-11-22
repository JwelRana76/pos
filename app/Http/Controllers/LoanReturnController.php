<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanReturn;
use App\Service\LoanReturnService;
use Illuminate\Http\Request;

class LoanReturnController extends Controller
{
    public function __construct()
    {
        $this->baseService = new LoanReturnService;
    }
    public function index($id)
    {
        $loan = Loan::find($id);
        $return_list = LoanReturn::where('loan_id', $id)->get();
        return ['item' => $return_list, 'balance' => $loan->balance];
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
        return redirect()->route('loan.index')->with($message);
    }
    public function update(Request $request)
    {
        if ($request->id == null) {
            $request->validate([
                'amount' => 'required',
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->update($data);
        return redirect()->route('loan.index')->with($message);
    }
    function delete($id)
    {
        LoanReturn::findOrFail($id)->delete();
        return redirect()->route('loan.index')->with('success', 'Loan Return Deleted Successfully');
    }
}
