<?php

namespace App\Http\Controllers;

use App\Models\Invest;
use App\Models\InvestReturn;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvestReturnController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $return_data['amount'] = $request->amount;
            $return_data['created_at'] = $request->date;
            $return_data['invest_id'] = $request->invest_id;
            $return_data['user_id'] = Auth::user()->id;
            InvestReturn::create($return_data);
            DB::commit();
            return redirect()->route('invest.index')->with('success', 'Invest Return Inserted Successfully');
        } catch (Exception $th) {
            DB::rollBack();
            dd($th->getMessage());
        }
    }
    public function view($id)
    {
        $invest = Invest::find($id);
        $return_list = InvestReturn::where('invest_id', $id)->get();
        return ['item' => $return_list, 'balance' => $invest->balance];
    }
    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $return_data['amount'] = $request->amount;
            $return_data['created_at'] = $request->date;
            $return_data['user_id'] = Auth::user()->id;
            InvestReturn::findOrFail($request->return_id)->update($return_data);
            DB::commit();
            return redirect()->route('invest.index')->with('success', 'Invest Return Updated Successfully');
        } catch (Exception $th) {
            DB::rollBack();
            dd($th->getMessage());
        }
    }
    public function delete($id)
    {
        InvestReturn::find($id)->delete();
        return redirect()->route('invest.index')->with('success', 'Invest Return Inserted Successfully');
    }
}
