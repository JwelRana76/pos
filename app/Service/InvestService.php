<?php

namespace App\Service;

use App\Models\Invest;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InvestService
{
    protected $model = Invest::class;

    public function Index()
    {
        $data = $this->model::all();

        return DataTables::of($data)
            ->addColumn('date', function ($item) {
                return $item->created_at->format('d-M-Y');
            })
            ->addColumn('entry_by', function ($item) {
                return $item->user->name ?? 'N/A';
            })
            ->addColumn('return', function ($item) {
                return $item->return ?? 'N/A';
            })
            ->addColumn('invest_to', function ($item) {
                return $item->type == 0 ? 'Cash' : 'Bank';
            })
            ->addColumn('action', fn($item) => view('pages.invest.action', compact('item'))->render())
            ->make(true);
    }
    public function Trash()
    {
        $data = $this->model::onlyTrashed();

        return DataTables::of($data)
            ->addColumn('date', function ($item) {
                return $item->created_at->format('d-M-Y');
            })
            ->addColumn('entry_by', function ($item) {
                return $item->user->name ?? 'N/A';
            })
            ->addColumn('invest_to', function ($item) {
                return $item->type == 0 ? 'Cash' : 'Bank';
            })
            ->addColumn('action', fn($item) => view('pages.invest.taction', compact('item'))->render())
            ->make(true);
    }
    public function create($data)
    {
        DB::beginTransaction();
        try {
            if ($data['id'] == null) {
                $this->model::create([
                    'created_at' => $data['date'],
                    'type' => $data['type'],
                    'amount' => $data['amount'],
                    'account_id' => $data['type'] == 0 ? $data['account_id'] : null,
                    'bank_id' => $data['type'] == 1 ? $data['bank_id'] : null,
                    'note' => $data['note'],
                    'user_id' => Auth::user()->id,
                ]);
                $message = ['success' => 'Invest Inserted Successfully'];
            } else {
                $this->model::findOrFail($data['id'])->update([
                    'created_at' => $data['date'],
                    'type' => $data['type'],
                    'amount' => $data['amount'],
                    'account_id' => $data['type'] == 0 ? $data['account_id'] : null,
                    'bank_id' => $data['type'] == 1 ? $data['bank_id'] : null,
                    'note' => $data['note'],
                    'user_id' => Auth::user()->id,
                ]);
                $message = ['success' => 'Invest Category Updated Successfully'];
            }
            DB::commit();
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
}
