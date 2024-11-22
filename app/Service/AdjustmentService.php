<?php

namespace App\Service;

use App\Models\Adjustment;
use App\Models\ProductAdjustment;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\VarDumper\Exception\ThrowingCasterException;

class AdjustmentService
{
    protected $model = Adjustment::class;
    protected $product = ProductAdjustment::class;

    function create($data)
    {
        DB::beginTransaction();
        try {
            $adjustment_data['created_at'] = $data['date'];
            $adjustment_data['user_id'] = Auth::user()->id;
            $adjustment_data['qty'] = array_sum($data['qty']);
            $adjustment = $this->model::create($adjustment_data);
            foreach ($data['product_id'] as $key => $product_id) {
                $product_data['product_id'] = $product_id;
                $product_data['adjustment_id'] = $adjustment->id;
                $product_data['qty'] = $data['qty'][$key];
                $product_data['type'] = $data['type'][$key];

                $this->product::create($product_data);
            }
            DB::commit();
            $message = ['success' => 'Adjustment Inserted Successfully'];
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
    function update($data, $id)
    {
        DB::beginTransaction();
        try {
            $adjustment = $this->model::findOrFail($id);
            $adjustment_data['created_at'] = $data['date'];
            $adjustment_data['user_id'] = Auth::user()->id;
            $adjustment_data['qty'] = array_sum($data['qty']);
            $adjustment->update($adjustment_data);
            $adjustment->products()->delete();
            foreach ($data['product_id'] as $key => $product_id) {
                $product_data['product_id'] = $product_id;
                $product_data['adjustment_id'] = $adjustment->id;
                $product_data['qty'] = $data['qty'][$key];
                $product_data['type'] = $data['type'][$key];

                $this->product::create($product_data);
            }
            DB::commit();
            $message = ['success' => 'Adjustment Updated Successfully'];
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
}
