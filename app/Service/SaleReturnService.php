<?php

namespace App\Service;

use App\Models\Account;
use App\Models\Product;
use App\Models\ProductSaleReturn;
use App\Models\SaleReturn;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleReturnService
{
    protected $model = SaleReturn::class;
    protected $product = Product::class;
    protected $product_return = ProductSaleReturn::class;

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
            $account = Account::whereFirst('is_default', 1);
            $return_data['created_at'] = $data['date'];
            $return_data['account_id'] = $data['account_id'] ?? $account->id;
            $return_data['voucher_no'] = $this->voucher_no();
            $return_data['customer_id'] = $data['customer'];
            $return_data['grand_total'] = $data['grand_total'];
            $return_data['qty'] = array_sum($data['qty']);
            $return_data['user_id'] = Auth::user()->id;
            $return = $this->model::create($return_data);
            $products = $data['product_id'];
            foreach ($products as $key => $product_id) {
                $product_return['sale_return_id'] = $return->id;
                $product_return['product_id'] = $product_id;
                $product_return['unit_price'] = $data['price'][$key];
                $product_return['qty'] = $data['qty'][$key];
                $product_return['total_price'] = $data['qty'][$key] * $data['price'][$key];
                $this->product_return::create($product_return);
            }
            $message = ['success' => 'Sale Return Inserted Successfully'];
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
            $account = Account::whereFirst('is_default', 1);
            $sale_return = $this->model::findOrFail($id);
            $return_data['created_at'] = $data['date'];
            $return_data['account_id'] = $data['account_id'] ?? $account->id;
            $return_data['customer_id'] = $data['customer'];
            $return_data['grand_total'] = $data['grand_total'];
            $return_data['qty'] = array_sum($data['qty']);
            $return_data['user_id'] = Auth::user()->id;
            $sale_return->sale_product_return()->delete();
            $products = $data['product_id'];
            foreach ($products as $key => $product_id) {
                $product_return['sale_return_id'] = $sale_return->id;
                $product_return['product_id'] = $product_id;
                $product_return['unit_price'] = $data['price'][$key];
                $product_return['qty'] = $data['qty'][$key];
                $product_return['total_price'] = $data['qty'][$key] * $data['price'][$key];
                $this->product_return::create($product_return);
            }
            $sale_return->update($return_data);
            $message = ['success' => 'Sale Return Updated Successfully'];
            DB::commit();
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
}
