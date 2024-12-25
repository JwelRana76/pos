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
            ->addColumn('default', function ($item) {
                return '<input type="checkbox" data-size="small" class="toggle-switch" data-id="' . $item->id . '" data-toggle="toggle" ' . ($item->is_default ? 'checked' : '') . '>';
            })
            ->addColumn('action', fn($item) => view('pages.account.action', compact('item'))->render())
            ->rawColumns(['action', 'default'])
            ->make(true);
    }
    public function create($data)
    {
        DB::beginTransaction();
        try {
            if ($data['id'] == null) {
                $account = $this->model::create([
                    'name' => $data['name'],
                    'account_no' => $data['account_no'],
                    'is_default' => $data['is_default']
                ]);
                if ($account->is_default == true) {
                    $accounts = $this->model::where('id', '!=', $account->id)->get();
                    foreach ($accounts as $key => $item) {
                        if ($item->is_default == true) {
                            $this->model::findOrFail($item->id)->update(['is_default' => false]);
                        }
                    }
                }
                $message = ['success' => 'Account Inserted Successfully'];
            } else {
                $account = $this->model::findOrFail($data['id'])->update([
                    'name' => $data['name'],
                    'account_no' => $data['account_no'],
                    'is_default' => $data['is_default']
                ]);
                if ($account->is_default == true) {
                    $accounts = $this->model::where('id', '!=', $account->id)->get();
                    foreach ($accounts as $key => $item) {
                        if ($item->is_default == true) {
                            $this->model::findOrFail($item->id)->update(['is_default' => false]);
                        }
                    }
                }
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
