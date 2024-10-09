<?php

namespace App\Service;

use App\Models\Account;
use App\Service\Service;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AccountService extends Service
{
    protected $model = Account::class;

    public function Index()
    {
        $data = $this->model::get();

        return DataTables::of($data)
            ->addColumn('balance', function ($item) {
                return $item->balance ?? 0;
            })
            ->addColumn('action', fn($item) => view('pages.account.action', compact('item'))->render())
            ->make(true);
    }
    public function create($data)
    {
        DB::beginTransaction();
        try {
            if ($data['id'] == null) {
                $this->model::create([
                    'name' => $data['name'],
                    'account_no' => $data['account_no'],
                    'is_default' => $data['is_default']
                ]);
                $message = ['success' => 'Account Inserted Successfully'];
            } else {
                $this->model::findOrFail($data['id'])->update([
                    'name' => $data['name'],
                    'account_no' => $data['account_no'],
                    'is_default' => $data['is_default']
                ]);
                $message = ['success' => 'Account Category Updated Successfully'];
            }
            DB::commit();
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
}
