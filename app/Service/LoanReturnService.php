<?php

namespace App\Service;

use App\Models\Loan;
use App\Models\LoanReturn;
use Exception;
use Illuminate\Cache\Events\RetrievingKey;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanReturnService
{
    protected $model = LoanReturn::class;
    protected $loan = Loan::class;

    function create($data)
    {
        DB::beginTransaction();
        try {
            $return_data['created_at'] = $data['date'];
            $return_data['amount'] = $data['amount'];
            $return_data['loan_id'] = $data['loan_id'];
            $return_data['user_id'] = Auth::user()->id;
            $return_data['note'] = $data['note'];
            $this->model::create($return_data);
            DB::commit();
            $message = ['success' => 'Loan Return Inserted Successfully'];
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
    function update($data)
    {
        DB::beginTransaction();
        try {
            $return = $this->model::findOrFail($data['return_id']);;
            $return_data['created_at'] = $data['date'];
            $return_data['amount'] = $data['amount'];
            $return_data['user_id'] = Auth::user()->id;
            $return_data['note'] = $data['note'];
            $return->update($return_data);
            DB::commit();
            $message = ['success' => 'Loan Return Updated Successfully'];
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
}
