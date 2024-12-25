<?php

namespace App\Service;

use App\Models\Loan;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class LoanService
{
    protected $model = Loan::class;

    public function Index()
    {
        $data = $this->model::all();
        foreach ($data as $loan) {
            if ($loan->balance > 0 && $loan->created_at < today()) {
            }
        }
        return DataTables::of($data)
            ->addColumn('date', function ($item) {
                return $item->created_at->format('d-M-Y');
            })
            ->addColumn('type', function ($item) {
                return $item->loan_type == true ? '<span class="badge badge-sm text-white bg-danger">Give</span>' : '<span class="text-white badge badge-sm bg-success">Take</span>';
            })
            ->addColumn('return', function ($item) {
                return $item->return ?? 0;
            })
            ->addColumn('action', fn($item) => view('pages.loan.action', compact('item'))->render())
            ->rawColumns(['action', 'type'])
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
                return $item->loan_type == true ? '<span class="badge badge-sm text-white bg-danger">Give</span>' : '<span class="text-white badge badge-sm bg-success">Take</span>';
            })
            ->addColumn('return', function ($item) {
                return $item->return ?? 0;
            })
            ->addColumn('action', fn($item) => view('pages.loan.taction', compact('item'))->render())
            ->rawColumns(['action', 'type'])
            ->make(true);
    }
    public function create($data)
    {
        DB::beginTransaction();
        try {
            $loan_data['created_at'] = $data['date'];
            $loan_data['account_id'] = $data['account'];
            $loan_data['name'] = $data['name'];
            $loan_data['contact'] = $data['contact'];
            $loan_data['amount'] = $data['amount'];
            $loan_data['loan_type'] = $data['loan_type'];
            $loan_data['note'] = $data['note'];
            $loan_data['user_id'] = Auth::user()->id;
            $this->model::create($loan_data);
            DB::commit();
            $message = ['success' => 'Loan Inserted Successfully'];
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
            $loan = $this->model::findOrFail($id);
            $loan_data['created_at'] = $data['date'];
            $loan_data['account_id'] = $data['account'];
            $loan_data['name'] = $data['name'];
            $loan_data['contact'] = $data['contact'];
            $loan_data['amount'] = $data['amount'];
            $loan_data['loan_type'] = $data['loan_type'];
            $loan_data['note'] = $data['note'];
            $loan_data['user_id'] = Auth::user()->id;
            $loan->update($loan_data);
            DB::commit();
            $message = ['success' => 'Loan Updated Successfully'];
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
}