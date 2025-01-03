<?php

namespace App\Service;

use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Models\Supplier;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SupplierService
{
    protected $model = Supplier::class;

    public function Index()
    {
        $data = $this->model::get();

        return DataTables::of($data)
            ->addColumn('image', function ($item) {
                if ($item->image) {
                    $img = '<img src="/upload/supplier/' . $item->image . '" alt="logo" width="80px">';
                } else {
                    $img = '<img src="/default_supplier.jpg" alt="logo" width="80px" height="60px">';
                }
                return $img;
            })
            ->addColumn('due', function ($item) {
                return $item->due;
            })
            ->addColumn('district', function ($item) {
                return $item->district->name ?? 'N/A';
            })
            ->addColumn('action', fn($item) => view('pages.supplier.action', compact('item'))->render())
            ->rawColumns(['image', 'action'])
            ->make(true);
    }
    public function Trash()
    {
        $data = $this->model::onlyTrashed();

        return DataTables::of($data)
            ->addColumn('image', function ($item) {
                if ($item->image) {
                    $img = '<img src="/upload/supplier/' . $item->image . '" alt="logo" width="80px">';
                } else {
                    $img = '<img src="/default_supplier.jpg" alt="logo" width="80px" height="60px">';
                }
                return $img;
            })
            ->addColumn('due', function ($item) {
                return $item->due;
            })
            ->addColumn('district', function ($item) {
                return $item->district->name ?? 'N/A';
            })
            ->addColumn('action', fn($item) => view('pages.supplier.taction', compact('item'))->render())
            ->rawColumns(['image', 'action'])
            ->make(true);
    }
    public function create($data)
    {
        DB::beginTransaction();
        try {
            $supplier_data['name'] = $data['name'];
            $supplier_data['contact'] = $data['contact'];
            $supplier_data['district_id'] = $data['district'];
            $supplier_data['email'] = $data['email'];
            $supplier_data['address'] = $data['address'];
            $supplier_data['opening_due'] = $data['opening_due'] ?? 0;
            if (isset($array['image'])) {
                $path = "upload/supplier";
                $file = $data['image'];
                $name = time() . '.' . $file->getClientOriginalExtension();
                $file->move($path, $name);
                $supplier_data['image'] = $name;
            }
            $this->model::create($supplier_data);
            $message = ['success' => 'Supplier Inserted Successfully'];
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
            $supplier = $this->model::findOrFail($id);
            $supplier_data['name'] = $data['name'];
            $supplier_data['contact'] = $data['contact'];
            $supplier_data['district_id'] = $data['district'];
            $supplier_data['email'] = $data['email'];
            $supplier_data['address'] = $data['address'];
            $supplier_data['opening_due'] = $data['opening_due'];
            if (isset($array['image'])) {

                $uploadDirectory = 'upload/supplier/';
                $existingImagePath = $uploadDirectory . $supplier->image;
                if (file_exists($existingImagePath) && $supplier->image) {
                    unlink($existingImagePath);
                }

                $path = "upload/supplier";
                $file = $data['image'];
                $name = time() . '.' . $file->getClientOriginalExtension();
                $file->move($path, $name);
                $supplier_data['image'] = $name;
            }
            $supplier->update($supplier_data);
            $message = ['success' => 'Supplier Updated Successfully'];
            DB::commit();
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }

    function purchaseDetails($id)
    {
        $purchases = Purchase::where('supplier_id', $id)->where('payment_status', false)
            ->select('purchases.id as id', 'purchases.voucher_no as voucher_no')->get();
        $pur = Purchase::where('supplier_id', $id)->where('payment_status', false)->get();
        $supplier = Supplier::findOrFail($id);
        $payable = $pur->sum(function ($pur) {
            return $pur->grand_total - $pur->paid;
        });
        $payable += $supplier->opening_balance;
        return ['purchases' => $purchases, 'due' => $payable];
    }
    function payment($data)
    {
        DB::beginTransaction();
        try {
            $payment_data['supplier_id'] = $data['supplier'];
            $payment_data['created_at'] = $data['date'];
            $payment_data['note'] = $data['note'];
            $payment_data['payment_method']  = $data['payment_method'];
            $payment_data['account_id']  = $data['payment_method'] == 0 ? $data['account'] : null;
            $payment_data['bank_id']  = $data['payment_method'] == 1 ? $data['bank'] : null;
            if ($data['voucher']) {
                $purchase = Purchase::findOrFail($data['voucher']);

                $payment_data['purchase_id'] = $data['voucher'];
                $payment_data['amount'] = $data['paid'];
                $payment_data['is_due'] = $data['date'] == $purchase->created_at->format('Y-m-d') ? false : true;
                $payment = PurchasePayment::create($payment_data);

                // Purchase payment status update 
                if ($purchase->grand_total - $purchase->paid == 0) {
                    $purchase->payment_status = true;
                    $purchase->save();
                }
            } else {
                $amount = $data['paid'];
                $supplier = Supplier::findOrFail($data['supplier']);
                if ($supplier->opening_balance > 0) {
                    $payable = $supplier->opening_balance;
                    $supplier->opening_due_paid += $supplier->opening_balance > $amount ? $amount : $supplier->opening_balance;
                    $supplier->save();
                    $payment_data['is_due'] = true;
                    $payment_data['amount'] = $supplier->opening_balance > $amount ? $amount : $payable;
                    $payment = PurchasePayment::create($payment_data);
                    $amount -= $payment->amount;
                }
                while ($amount > 0) {
                    $purchase = Purchase::where('supplier_id', $data['supplier'])->orderBy('id', 'asc')->where('payment_status', false)->first();
                    if (!$purchase) {
                        dd($amount);
                    }
                    $due = $purchase->due;
                    if ($amount >= $due) {
                        $payment_data['purchase_id'] = $purchase->id;
                        $payment_data['is_due'] = $data['date'] == $purchase->created_at->format('Y-m-d') ? false : true;
                        $payment_data['amount'] = $purchase->grand_total - $purchase->paid;
                        $amount = $amount - ($purchase->grand_total - $purchase->paid);
                        $purchase->payment_status = true;
                        $purchase->save();
                    } else {
                        $payment_data['purchase_id'] = $purchase->id;
                        $payment_data['is_due'] = $data['date'] == $purchase->created_at->format('Y-m-d') ? false : true;
                        $payment_data['amount'] = $amount;
                        $amount = 0;
                    }
                    $payment = PurchasePayment::create($payment_data);
                }
            }
            $message = ['success' => 'Supplier Payment Insert Successfully'];
            DB::commit();
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage(), $th->getLine());
        }
    }
    function paymentDetails($id)
    {
        $paymentlist = PurchasePayment::join('purchases', 'purchases.id', 'purchase_pyaments.purchase_id')
            ->where('purchase_pyaments.supplier_id', $id)
            ->select('purchase_pyaments.*', 'purchases.voucher_no as voucher_no')
            ->get();
        return $paymentlist;
    }
}
