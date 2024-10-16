<?php

namespace App\Service;

use App\Models\Product;
use App\Models\ProductPurchaseReturn;
use App\Models\PurchaseReturn;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseReturnService
{
    protected $model = PurchaseReturn::class;
    protected $product = Product::class;
    protected $product_return = ProductPurchaseReturn::class;


    function voucher_no()
    {
        $purchase = $this->model::orderBy('id', 'desc')->first();
        if ($purchase) {
            $voucher_no = explode('-', $purchase->voucher_no)[1] + 1;
            if ($voucher_no < 10) {
                $item = 'return-0000' . $voucher_no;
            } elseif ($voucher_no < 100) {
                $item = 'return-000' . $voucher_no;
            } elseif ($voucher_no < 1000) {
                $item = 'return-00' . $voucher_no;
            } elseif ($voucher_no < 10000) {
                $item = 'return-0' . $voucher_no;
            } else {
                $item = 'return-' . $voucher_no;
            }
            return $item;
        } else {
            return 'return-00001';
        }
    }

    public function create($data)
    {
        DB::beginTransaction();
        try {
            $return_data['created_at'] = $data['date'];
            $return_data['account_id'] = $data['account_id'] ?? 1;
            $return_data['voucher_no'] = $this->voucher_no();
            $return_data['supplier_id'] = $data['supplier'];
            $return_data['grand_total'] = $data['grand_total'];
            $return_data['qty'] = array_sum($data['qty']);
            $return_data['user_id'] = Auth::user()->id;
            $return = $this->model::create($return_data);
            $products = $data['product_id'];
            foreach ($products as $key => $product_id) {
                $product_return['purchase_return_id'] = $return->id;
                $product_return['product_id'] = $product_id;
                $product_return['unit_cost'] = $data['cost'][$key];
                $product_return['qty'] = $data['qty'][$key];
                $product_return['total_cost'] = $data['qty'][$key] * $data['cost'][$key];
                $this->product_return::create($product_return);
            }
            $message = ['success' => 'Purchase Return Inserted Successfully'];
            DB::commit();
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage(), $th->getLine());
        }
    }
    public function update($data, $id)
    {
        DB::beginTransaction();
        try {
            $purchase_return = $this->model::findOrFail($id);
            $return_data['created_at'] = $data['date'];
            $return_data['account_id'] = $data['account_id'] ?? 1;
            $return_data['voucher_no'] = $data['voucher_no'];
            $return_data['supplier_id'] = $data['supplier'];
            $return_data['grand_total'] = $data['grand_total'];
            $return_data['qty'] = array_sum($data['qty']);
            $return_data['user_id'] = Auth::user()->id;
            $purchase_return->purchase_product_return()->delete();
            $products = $data['product_id'];
            foreach ($products as $key => $product_id) {
                $product_return['purchase_return_id'] = $purchase_return->id;
                $product_return['product_id'] = $product_id;
                $product_return['unit_cost'] = $data['cost'][$key];
                $product_return['qty'] = $data['qty'][$key];
                $product_return['total_cost'] = $data['qty'][$key] * $data['cost'][$key];
                $this->product_return::create($product_return);
            }
            $purchase_return->update($return_data);
            $message = ['success' => 'Purchase Return Updated Successfully'];
            DB::commit();
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
}
