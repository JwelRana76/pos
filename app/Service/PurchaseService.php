<?php

namespace App\Service;

use App\Models\Account;
use App\Models\Product;
use App\Models\ProductPurchase;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    protected $model = Purchase::class;
    protected $product = Product::class;
    protected $product_purchase = ProductPurchase::class;
    protected $payment = PurchasePayment::class;

    function voucher_no()
    {
        $purchase = $this->model::orderBy('id', 'desc')->first();
        if ($purchase) {
            $voucher_no = explode('-', $purchase->voucher_no)[1] + 1;
            if ($voucher_no < 10) {
                $item = 'pur-0000' . $voucher_no;
            } elseif ($voucher_no < 100) {
                $item = 'pur-000' . $voucher_no;
            } elseif ($voucher_no < 1000) {
                $item = 'pur-00' . $voucher_no;
            } elseif ($voucher_no < 10000) {
                $item = 'pur-0' . $voucher_no;
            } else {
                $item = 'pur-' . $voucher_no;
            }
            return $item;
        } else {
            return 'pur-00001';
        }
    }
    public function create($data)
    {
        DB::beginTransaction();
        try {
            $purchase_data['created_at'] = $data['date'];
            $purchase_data['voucher_no'] = $this->voucher_no();
            $purchase_data['supplier_id'] = $data['supplier'];
            $purchase_data['total_amount'] = $data['total_price'];
            $purchase_data['discount'] = $data['discount_amount'];
            $purchase_data['previous_due'] = $data['previous_due'];
            $purchase_data['shipping_cost'] = $data['shipping_cost'];
            $purchase_data['grand_total'] = $data['grand_total'];
            $purchase_data['user_id'] = Auth::user()->id;
            $purchase_data['payment_status'] = ($data['paid_amount'] >= $data['grand_total'] ? true : false);
            $purchase = $this->model::create($purchase_data);
            $products = $data['product_id'];
            foreach ($products as $key => $product_id) {
                $product_purchase['purchase_id'] = $purchase->id;
                $product_purchase['product_id'] = $product_id;
                $product_purchase['unit_cost'] = $data['cost'][$key];
                $product_purchase['qty'] = $data['qty'][$key];
                $product_purchase['total_cost'] = $data['qty'][$key] * $data['cost'][$key];
                $this->product_purchase::create($product_purchase);

                // product cost calculation part
                $product = $this->product::findOrFail($product_id);
                $old_stock = $product->stock - $data['qty'][$key];
                $old_cost = $old_stock * $product->cost;
                $new_stock = $old_stock + $data['qty'][$key];
                $new_cost = $old_cost + ($data['qty'][$key] * $data['cost'][$key]);
                $final_cost = $new_cost / $new_stock;
                $product->cost = $final_cost;
                $product->save();
            }
            if ($data['paid_amount']) {
                $account = Account::where('is_default', true)->first()->id;
                $paid_amount = $data['paid_amount'];
                $payment_data['supplier_id'] = $purchase->supplier_id;
                $payment_data['purchase_id'] = $purchase->id;
                $payment_data['amount'] = $paid_amount;
                $payment_data['account_id'] = $account;
                $payment_data['payment_method'] = 0;
                $this->payment::create($payment_data);
            }
            $message = ['success' => 'Product Purchase Inserted Successfully'];
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
            $purchase = $this->model::findOrFail($id);
            $purchase_data['created_at'] = $data['date'];
            $purchase_data['voucher_no'] = $data['voucher_no'];
            $purchase_data['supplier_id'] = $data['supplier'];
            $purchase_data['total_amount'] = $data['total_price'];
            $purchase_data['discount'] = $data['discount_amount'];
            $purchase_data['previous_due'] = $data['previous_due'];
            $purchase_data['shipping_cost'] = $data['shipping_cost'];
            $purchase_data['grand_total'] = $data['grand_total'];
            $purchase_data['user_id'] = Auth::user()->id;
            $purchase_data['payment_status'] = ($data['paid_amount'] >= $data['grand_total'] ? true : false);
            $product_purchases = $purchase->product_purchase;
            foreach ($product_purchases as $key => $purchase_product) {
                $product = $this->product::findOrFail($purchase_product->product_id);
                $old_stock = $product->stock;
                $old_cost = $product->stock * $product->cost;
                $new_stock = $old_stock - $purchase_product->qty;
                $new_cost = $old_cost - ($purchase_product->unit_cost * $purchase_product->qty);
                $final_cost = $new_cost / ($new_stock == 0 ? 1 : $new_stock);
                $product->cost = $final_cost;
                $product->save();
                $purchase_product->delete();
            }
            $products = $data['product_id'];
            foreach ($products as $key => $product_id) {
                $product_purchase['purchase_id'] = $purchase->id;
                $product_purchase['product_id'] = $product_id;
                $product_purchase['unit_cost'] = $data['cost'][$key];
                $product_purchase['qty'] = $data['qty'][$key];
                $product_purchase['total_cost'] = $data['qty'][$key] * $data['cost'][$key];
                $this->product_purchase::create($product_purchase);

                // product cost calculation part
                $product = $this->product::findOrFail($product_id);
                $old_stock = $product->stock - $data['qty'][$key];
                $old_cost = $old_stock * $product->cost;
                $new_stock = $old_stock + $data['qty'][$key];
                $new_cost = $old_cost + ($data['qty'][$key] * $data['cost'][$key]);
                $final_cost = $new_cost / $new_stock;
                $product->cost = $final_cost;
                $product->save();
            }
            // payment section code 
            $purchase->payments()->delete();
            if ($data['paid_amount']) {
                $account = Account::where('is_default', true)->first()->id;
                $payment_data['account_id'] = $account;
                $paid_amount = $data['paid_amount'];
                $payment_data['supplier_id'] = $purchase->supplier_id;
                $payment_data['purchase_id'] = $purchase->id;
                $payment_data['amount'] = $paid_amount;
                $payment_data['payment_method'] = 0;
                $this->payment::create($payment_data);
            }
            $purchase->update($purchase_data);
            $message = ['success' => 'Purchase Updated Successfully'];
            DB::commit();
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
    public function productSearch($search)
    {
        $products = Product::orderby('name', 'asc')
            ->select('id', 'name', 'code', 'cost', 'price')
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%');
            })
            ->limit(10)
            ->get();

        $results = [];

        foreach ($products as $product) {
            $results[] = [
                'id' => $product->id,
                'value' => $product->name, // jQuery UI Autocomplete expects a 'value' key for display
                'code' => $product->code,
                'cost' => $product->cost,
                'price' => $product->price,
                'stock' => $product->stock,
            ];
        }
        return $results;
    }
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $purchase = $this->model::findOrFail($id);
            $product_purchases = $purchase->product_purchase;
            foreach ($product_purchases as $key => $purchase_product) {
                $product = $this->product::findOrFail($purchase_product->product_id);
                $old_stock = $product->stock;
                $old_cost = $product->stock * $product->cost;
                $new_stock = $old_stock - $purchase_product->qty;
                $new_cost = $old_cost - ($purchase_product->unit_cost * $purchase_product->qty);
                if ($new_cost != 0) {
                    $final_cost = $new_cost / ($new_stock == 0 ? 1 : $new_stock);
                    $product->cost = $final_cost;
                    $product->save();
                }
            }
            $purchase->delete();
            DB::commit();
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $purchase = $this->model::onlyTrashed($id);
            $product_purchases = ProductPurchase::where('purchase_id', $id)->get();
            foreach ($product_purchases as $key => $purchase_product) {
                $product = $this->product::findOrFail($purchase_product->product_id);
                $old_stock = $product->stock;
                $old_cost = $product->stock * $product->cost;
                $new_stock = $old_stock + $purchase_product->qty;
                $new_cost = $old_cost + ($purchase_product->unit_cost * $purchase_product->qty);
                $final_cost = $new_cost / ($new_stock == 0 ? 1 : $new_stock);
                $product->cost = $final_cost;
                $product->save();
            }
            $purchase->restore();
            DB::commit();
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
}
