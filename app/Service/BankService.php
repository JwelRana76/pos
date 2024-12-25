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
            })->addColumn('default', function ($item) {
                return '<input type="checkbox" data-size="small" class="toggle-switch" data-id="' . $item->id . '" data-toggle="toggle" ' . ($item->is_default ? 'checked' : '') . '>';
            })
            ->addColumn('action', fn($item) => view('pages.bank.action', compact('item'))->render())
            ->rawColumns(['action', 'default'])
            ->make(true);
    }
    public function create($data)
    {
        DB::beginTransaction();
        try {
            if ($data['id'] == null) {
                $bank = $this->model::create([
                    'holder_name' => $data['holder_name'],
                    'account_no' => $data['account_no'],
                    'bank_name' => $data['bank_name'],
                    'branch' => $data['branch'],
                ]);
                if ($bank->is_default == true) {
                    $banks = $this->model::where('id', '!=', $bank->id)->get();
                    foreach ($banks as $key => $item) {
                        if ($item->is_default == true) {
                            $this->model::findOrFail($item->id)->update(['is_default' => false]);
                        }
                    }
                }
                $message = ['success' => 'Bank Inserted Successfully'];
            } else {
                $bank = $this->model::findOrFail($data['id'])->update([
                    'holder_name' => $data['holder_name'],
                    'account_no' => $data['account_no'],
                    'bank_name' => $data['bank_name'],
                    'branch' => $data['branch'],
                ]);
                if ($bank->is_default == true) {
                    $banks = $this->model::where('id', '!=', $bank->id)->get();
                    foreach ($banks as $key => $item) {
                        if ($item->is_default == true) {
                            $this->model::findOrFail($item->id)->update(['is_default' => false]);
                        }
                    }
                }
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
