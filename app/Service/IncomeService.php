<?php

namespace App\Service;

use App\Models\Income;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class IncomeService
{
    protected $model = Income::class;

    public function Index()
    {
        $data = $this->model::all();

        return DataTables::of($data)
            ->addColumn('date', function ($item) {
                return $item->created_at->format('d-M-Y');
            })
            ->addColumn('category', function ($item) {
                return $item->category->name ?? 'N/A';
            })
            ->addColumn('entry_by', function ($item) {
                return $item->user->name ?? 'N/A';
            })
            ->addColumn('action', fn($item) => view('pages.income.action', compact('item'))->render())
            ->make(true);
    }
    public function Trash()
    {
        $data = $this->model::onlyTrashed();

        return DataTables::of($data)
            ->addColumn('date', function ($item) {
                return $item->created_at->format('d-M-Y');
            })
            ->addColumn('category', function ($item) {
                return $item->category->name ?? 'N/A';
            })
            ->addColumn('entry_by', function ($item) {
                return $item->user->name ?? 'N/A';
            })
            ->addColumn('action', fn($item) => view('pages.income.taction', compact('item'))->render())
            ->make(true);
    }
    public function create($data)
    {
        DB::beginTransaction();
        try {
            if ($data['id'] == null) {
                $this->model::create([
                    'category_id' => $data['category_id'],
                    'amount' => $data['amount'],
                    'account_id' => $data['account_id'] ?? 1,
                    'note' => $data['note'],
                    'user_id' => Auth::user()->id,
                ]);
                $message = ['success' => 'Income Inserted Successfully'];
            } else {
                $this->model::findOrFail($data['id'])->update([
                    'category_id' => $data['category_id'],
                    'amount' => $data['amount'],
                    'account_id' => $data['account_id'] ?? 1,
                    'note' => $data['note'],
                    'user_id' => Auth::user()->id,
                ]);
                $message = ['success' => 'Income Category Updated Successfully'];
            }
            DB::commit();
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
}
