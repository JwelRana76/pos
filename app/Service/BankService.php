<?php

namespace App\Service;

use App\Models\Bank;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BankService
{
    protected $model = Bank::class;

    public function Index()
    {
        $data = $this->model::get();

        return DataTables::of($data)
            ->addColumn('balance', function ($item) {
                return $item->balance ?? 0;
            })
            ->addColumn('action', fn($item) => view('pages.bank.action', compact('item'))->render())
            ->make(true);
    }
    public function create($data)
    {
        DB::beginTransaction();
        try {
            if ($data['id'] == null) {
                $this->model::create([
                    'holder_name' => $data['holder_name'],
                    'account_no' => $data['account_no'],
                    'bank_name' => $data['bank_name'],
                    'branch' => $data['branch'],
                ]);
                $message = ['success' => 'Bank Inserted Successfully'];
            } else {
                $this->model::findOrFail($data['id'])->update([
                    'holder_name' => $data['holder_name'],
                    'account_no' => $data['account_no'],
                    'bank_name' => $data['bank_name'],
                    'branch' => $data['branch'],
                ]);
                $message = ['success' => 'Bank Category Updated Successfully'];
            }
            DB::commit();
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
}
