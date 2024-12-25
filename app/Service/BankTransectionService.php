<?php

namespace App\Service;

use App\Models\Account;
use App\Models\Bank;
use App\Models\BankTransection;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class BankTransectionService
{
    protected $model = BankTransection::class;

    public function Index()
    {
        $data = $this->model::all();
        return DataTables::of($data)
            ->addColumn('date', function ($item) {
                return $item->created_at->format('d-M-Y');
            })
            ->addColumn('type', function ($item) {
                return $item->type == true ? '<span class="badge badge-sm text-white bg-danger">Deposit</span>' : '<span class="text-white badge badge-sm bg-success">Withdraw</span>';
            })
            ->addColumn('account', function ($item) {
                return $item->account->name . '<br> ' . $item->account->account_no;
            })
            ->addColumn('bank', function ($item) {
                return $item->bank->bank_name . '<br>' . $item->bank->account_no;
            })
            ->addColumn('action', fn($item) => view('pages.bank_transection.action', compact('item'))->render())
            ->rawColumns(['action', 'account', 'bank', 'type'])
            ->make(true);
    }
    public function Trash()
    {
        $data = $this->model::onlyTrashed();

        return DataTables::of($data)
            ->addColumn('date', function ($item) {
                return $item->created_at->format('d-M-Y');
            })
            ->addColumn('type', function ($item) {
                return $item->type == true ? '<span class="badge badge-sm text-white bg-danger">Deposit</span>' : '<span class="text-white badge badge-sm bg-success">Withdraw</span>';
            })
            ->addColumn('account', function ($item) {
                return $item->account->name . $item->account->account_no;
            })
            ->addColumn('bank', function ($item) {
                return $item->bank->account_no ?? 0;
            })
            ->addColumn('action', fn($item) => view('pages.bank_transection.taction', compact('item'))->render())
            ->rawColumns(['action', 'type'])
            ->make(true);
    }
    public function create($data)
    {
        DB::beginTransaction();
        try {
            $transection_data['created_at'] = $data['date'];
            $transection_data['account_id'] = $data['account'] ?? Account::first()->id;
            $transection_data['bank_id'] = $data['bank'] ?? Bank::first()->id;
            $transection_data['type'] = $data['type'];
            $transection_data['amount'] = $data['amount'];
            $transection_data['user_id'] = Auth::user()->id;
            $this->model::create($transection_data);
            DB::commit();
            $message = ['success' => 'Bank_transection Inserted Successfully'];
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
    public function update($data, $id)
    {
        DB::beginTransaction();
        try {
            $transection = $this->model::findOrFail($id);
            $transection_data['created_at'] = $data['date'];
            $transection_data['account_id'] = $data['account'] ?? Account::first()->id;
            $transection_data['bank_id'] = $data['bank'] ?? Bank::first()->id;
            $transection_data['type'] = $data['type'];
            $transection_data['amount'] = $data['amount'];
            $transection_data['user_id'] = Auth::user()->id;
            $transection->update($transection_data);
            DB::commit();
            $message = ['success' => 'Bank Transection Updated Successfully'];
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
}
