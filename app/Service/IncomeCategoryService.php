<?php

namespace App\Service;

use App\Models\IncomeCategory;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class IncomeCategoryService
{
    protected $model = IncomeCategory::class;

    public function Index()
    {
        $data = $this->model::all();

        return DataTables::of($data)
            ->addColumn('action', fn($item) => view('pages.income_category.action', compact('item'))->render())
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
                $message = ['success' => 'Income Category Inserted Successfully'];
            } else {
                $this->model::findOrFail($data['id'])->update([
                    'name' => $data['name'],
                    'code' => $data['code'],
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
