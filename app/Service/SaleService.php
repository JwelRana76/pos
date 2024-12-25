<?php

namespace App\Service;

use App\Models\Account;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sale;
use App\Models\SalePayment;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SaleService
{
    protected $model = Sale::class;
    protected $product = Product::class;
    protected $product_sale = ProductSale::class;
    protected $payment = SalePayment::class;

    function voucher_no()
    {
        $sale = $this->model::orderBy('id', 'desc')->first();
        if ($sale) {
            $voucher_no = explode('-', $sale->voucher_no)[1] + 1;
            if ($voucher_no < 10) {
                $item = 'sale-0000' . $voucher_no;
            } elseif ($voucher_no < 100) {
                $item = 'sale-000' . $voucher_no;
            } elseif ($voucher_no < 1000) {
                $item = 'sale-00' . $voucher_no;
            } elseif ($voucher_no < 10000) {
                $item = 'sale-0' . $voucher_no;
            } else {
                $item = 'sale-' . $voucher_no;
            }
            return $item;
        } else {
            return 'sale-00001';
        }
    }
    public function create($data)
    {
        DB::beginTransaction();
        try {
            // dd($data);
            $sale_data['created_at'] = $data['date'];
            $sale_data['voucher_no'] = $this->voucher_no();
            $sale_data['customer_id'] = $data['customer'];
            $sale_data['total_amount'] = $data['total_price'];
            $sale_data['discount'] = $data['discount_amount'];
            $sale_data['previous_due'] = $data['previous_due'];
            $sale_data['shipping_cost'] = $data['shipping_cost'];
            $sale_data['grand_total'] = $data['grand_total'];
            $sale_data['user_id'] = Auth::user()->id;
            $sale_data['payment_status'] = ($data['paid_amount'] >= $data['grand_total'] ? true : false);
            $sale = $this->model::create($sale_data);
            $products = $data['product_id'];
            foreach ($products as $key => $product_id) {
                $product_sale['sale_id'] = $sale->id;
                $product_sale['product_id'] = $product_id;
                $product_sale['unit_cost'] = $data['cost'][$key];
                $product_sale['unit_price'] = $data['price'][$key];
                $product_sale['qty'] = $data['qty'][$key];
                $product_sale['total_price'] = $data['qty'][$key] * $data['price'][$key];
                // dd($product_sale);
                $product = $this->product::findOrFail($product_id);
                if ($data['qty'][$key] > $product->stock) {
                    return ['warning' => 'Product Out of Stock'];
                }
                $this->product_sale::create($product_sale);
            }
            if ($data['paid_amount']) {
                $account = Account::where('is_default', true)->first()->id;
                $payment_data['account_id'] = $account;
                $paid_amount = $data['paid_amount'];
                $payment_data['customer_id'] = $sale->customer_id;
                $payment_data['sale_id'] = $sale->id;
                $payment_data['amount'] = $paid_amount;
                $payment_data['payment_method'] = 0;
                $this->payment::create($payment_data);
            }
            $message = ['success' => 'Sale Inserted Successfully'];
            DB::commit();
            return $sale;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage(), $th->getLine());
        }
    }
    public function update($data, $id)
    {
        DB::beginTransaction();
        try {
            $sale = $this->model::findOrFail($id);
            $sale_data['created_at'] = $data['date'];
            $sale_data['voucher_no'] = $data['voucher_no'];
            $sale_data['customer_id'] = $data['customer'];
            $sale_data['total_amount'] = $data['total_price'];
            $sale_data['discount'] = $data['discount_amount'];
            $sale_data['previous_due'] = $data['previous_due'];
            $sale_data['shipping_cost'] = $data['shipping_cost'];
            $sale_data['grand_total'] = $data['grand_total'];
            $sale_data['user_id'] = Auth::user()->id;
            $sale_data['payment_status'] = ($data['paid_amount'] >= $data['grand_total'] ? true : false);

            $sale->product_sale()->delete();
            $products = $data['product_id'];
            foreach ($products as $key => $product_id) {
                $product_sale['sale_id'] = $sale->id;
                $product_sale['product_id'] = $product_id;
                $product_sale['unit_cost'] = $data['cost'][$key];
                $product_sale['unit_price'] = $data['price'][$key];
                $product_sale['qty'] = $data['qty'][$key];
                $product_sale['total_price'] = $data['qty'][$key] * $data['price'][$key];

                $product = $this->product::findOrFail($product_id);
                if ($data['qty'][$key] > $product->stock) {
                    return ['warning' => 'Product Out of Stock'];
                }
                $this->product_sale::create($product_sale);
            }
            // payment section code 
            $sale->payments()->delete();
            if ($data['paid_amount']) {
                $account = Account::where('is_default', true)->first()->id;
                $payment_data['account_id'] = $account;
                $paid_amount = $data['paid_amount'];
                $payment_data['customer_id'] = $sale->customer_id;
                $payment_data['sale_id'] = $sale->id;
                $payment_data['amount'] = $paid_amount;
                $payment_data['payment_method'] = 0;
                $this->payment::create($payment_data);
            }
            $sale->update($sale_data);
            $message = ['success' => 'Sale Updated Successfully'];
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
            ->get()
            ->filter(function ($product) {
                return $product->stock > 0;
            })
            ->take(10);

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
            $sale = $this->model::findOrFail($id);
            $sale->delete();
            DB::commit();
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
}
