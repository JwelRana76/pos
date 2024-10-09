<?php

namespace App\Service;

use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CustomerService
{
    protected $model = Customer::class;

    public function Index()
    {
        $data = $this->model::get();

        return DataTables::of($data)
            ->addColumn('image', function ($item) {
                if ($item->image) {
                    $img = '<img src="/upload/customer/' . $item->image . '" alt="logo" width="80px">';
                } else {
                    $img = '<img src="/upload/customer/default_product.jpg" alt="logo" width="80px">';
                }
                return $img;
            })
            ->addColumn('due', function ($item) {
                return $item->due;
            })
            ->addColumn('district', function ($item) {
                return $item->district->name ?? 'N/A';
            })
            ->addColumn('action', fn($item) => view('pages.customer.action', compact('item'))->render())
            ->rawColumns(['image', 'action'])
            ->make(true);
    }
    public function Trash()
    {
        $data = $this->model::onlyTrashed();

        return DataTables::of($data)
            ->addColumn('image', function ($item) {
                if ($item->image) {
                    $img = '<img src="/upload/customer/' . $item->image . '" alt="logo" width="80px">';
                } else {
                    $img = '<img src="/upload/customer/default_product.jpg" alt="logo" width="80px">';
                }
                return $img;
            })
            ->addColumn('due', function ($item) {
                return $item->due;
            })
            ->addColumn('district', function ($item) {
                return $item->district->name ?? 'N/A';
            })
            ->addColumn('action', fn($item) => view('pages.customer.taction', compact('item'))->render())
            ->rawColumns(['image', 'action'])
            ->make(true);
    }
    public function create($data)
    {
        DB::beginTransaction();
        try {
            $customer_data['name'] = $data['name'];
            $customer_data['contact'] = $data['contact'];
            $customer_data['district_id'] = $data['district'];
            $customer_data['email'] = $data['email'];
            $customer_data['address'] = $data['address'];
            $customer_data['opening_due'] = $data['opening_due'];
            if (isset($data['image'])) {
                $path = "upload/customer";
                $file = $data['image'];
                $name = time() . '.' . $file->getClientOriginalExtension();
                $file->move($path, $name);
                $customer_data['image'] = $name;
            }
            $this->model::create($customer_data);
            $message = ['success' => 'Customer Inserted Successfully'];
            DB::commit();
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
    public function update($data, $id)
    {
        DB::beginTransaction();
        try {
            $customer = $this->model::findOrFail($id);
            $customer_data['name'] = $data['name'];
            $customer_data['contact'] = $data['contact'];
            $customer_data['district_id'] = $data['district'];
            $customer_data['email'] = $data['email'];
            $customer_data['address'] = $data['address'];
            $customer_data['opening_due'] = $data['opening_due'];
            if (isset($data['image'])) {

                $uploadDirectory = 'upload/customer/';
                $existingImagePath = $uploadDirectory . $customer->image;
                if (file_exists($existingImagePath) && $customer->image) {
                    unlink($existingImagePath);
                }
                $path = "upload/customer";
                $file = $data['image'];
                $name = time() . '.' . $file->getClientOriginalExtension();
                $file->move($path, $name);
                $customer_data['image'] = $name;
            }
            $customer->update($customer_data);
            $message = ['success' => 'Customer Updated Successfully'];
            DB::commit();
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }

    function saleDetails($id)
    {
        $sales = Sale::where('customer_id', $id)->where('payment_status', false)
            ->select('sales.id as id', 'sales.voucher_no as voucher_no')->get();
        $pur = Sale::where('customer_id', $id)->where('payment_status', false)->get();
        $payable = $pur->sum(function ($pur) {
            return $pur->grand_total - $pur->paid;
        });
        return ['sales' => $sales, 'due' => $payable];
    }
    function payment($data)
    {
        DB::beginTransaction();
        try {
            $payment_data['customer_id'] = $data['customer_id'];
            $payment_data['created_at'] = $data['date'];
            $payment_data['note'] = $data['note'];
            $payment_data['payment_method']  = $data['payment_method'];
            $payment_data['account_id']  = $data['account'];
            $payment_data['bank_id']  = $data['bank'];
            if ($data['sale_id']) {
                $payment_data['sale_id'] = $data['sale_id'];
                $payment_data['amount'] = $data['paid'];
                $payment = SalePayment::create($payment_data);

                // Purchase payment status update 
                $sale = Sale::findOrFail($data['sale_id']);
                if ($sale->grand_total - $sale->paid == 0) {
                    $sale->payment_status = true;
                    $sale->save();
                }
            } else {
                $amount = $data['paid'];
                while ($amount > 0) {
                    $sale = Sale::where('customer_id', $data['customer_id'])->orderBy('id', 'asc')->where('payment_status', false)->first();
                    if ($amount >= ($sale->grand_total - $sale->paid)) {
                        $payment_data['sale_id'] = $sale->id;
                        $payment_data['amount'] = $sale->grand_total - $sale->paid;
                        $amount = $amount - ($sale->grand_total - $sale->paid);
                        $sale->payment_status = true;
                        $sale->save();
                    } else {
                        $payment_data['sale_id'] = $sale->id;
                        $payment_data['amount'] = $amount;
                        $amount = 0;
                    }
                    $payment = SalePayment::create($payment_data);
                }
            }
            $message = ['success' => 'Customer Payment Insert Successfully'];
            DB::commit();
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
    function paymentDetails($id)
    {
        $paymentlist = SalePayment::join('sales', 'sales.id', 'sale_payments.sale_id')
            ->where('sale_payments.customer_id', $id)
            ->select('sale_payments.*', 'sales.voucher_no as voucher_no')
            ->get();
        return $paymentlist;
    }
}
