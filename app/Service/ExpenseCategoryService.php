<?php

namespace App\Service;

use App\Models\ExpenseCategory;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ExpenseCategoryService extends Service
{
    protected $model = ExpenseCategory::class;

    public function Index()
    {
        $data = $this->model::all();

        return DataTables::of($data)
            ->addColumn('action', fn($item) => view('pages.expense_category.action', compact('item'))->render())
            ->make(true);
    }
    public function Trash()
    {
        $data = $this->model::onlyTrashed();

        return DataTables::of($data)
            ->addColumn('action', fn($item) => view('pages.expense_category.taction', compact('item'))->render())
            ->make(true);
    }
    public function create($data)
    {
        DB::beginTransaction();
        try {
            if ($data['id'] == null) {
                $this->model::create([
                    'name' => $data['name'],
                    'code' => $data['code'],
                ]);
                $message = ['success' => 'Expense Category Inserted Successfully'];
            } else {
                $this->model::findOrFail($data['id'])->update([
                    'name' => $data['name'],
                    'code' => $data['code'],
                ]);
                $message = ['success' => 'Expense Category Updated Successfully'];
            }
            DB::commit();
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
}
