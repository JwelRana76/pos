<?php

namespace App\Service;

use App\Models\SalaryParticular;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SalaryParticularService
{
    protected $model = SalaryParticular::class;

    public function Index()
    {
        $data = $this->model::active();

        return DataTables::of($data)
            ->addColumn('action', fn($item) => view('pages.salaryparticular.action', compact('item'))->render())
            ->make(true);
    }
    public function create($data)
    {
        DB::beginTransaction();
        try {
            if ($data['id'] == null) {
                $this->model::create([
                    'name' => $data['name'],
                    'is_provident' => $data['is_provident'] == 1 ? true : false,
                    'is_constant' => $data['is_constant'] == 1 ? true : false,
                ]);
                $message = ['success' => 'Salary Particular Inserted Successfully'];
            } else {
                $this->model::findOrFail($data['id'])->update([
                    'name' => $data['name'],
                    'is_provident' => $data['is_provident'] == 1 ? true : false,
                    'is_constant' => $data['is_constant'] == 1 ? true : false,
                ]);
                $message = ['success' => 'Salary Particular Updated Successfully'];
            }
            DB::commit();
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
}
