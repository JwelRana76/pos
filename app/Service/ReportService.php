<?php

namespace App\Service;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Product;
use App\Models\ProductAdjustment;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Models\PurchaseReturn;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\SaleReturn;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ReportService
{
    function Opening_stock($item, $data)
    {
        if (isset($data['start_date'])) {
            $purchase = Purchase::join('product_purchases', 'product_purchases.purchase_id', 'purchases.id')
                ->where('product_purchases.product_id', $item->id)
                ->when(isset($data['start_date']), function ($query) use ($data) {
                    $query->whereDate('purchases.created_at', '<', $data['start_date']);
                })
                ->sum('product_purchases.qty');
            $sale = Sale::join('product_sales', 'product_sales.sale_id', 'sales.id')
                ->where('product_sales.product_id', $item->id)
                ->when(isset($data['start_date']), function ($query) use ($data) {
                    $query->whereDate('sales.created_at', '<', $data['start_date']);
                })
                ->sum('product_sales.qty');
            $purchase_return = PurchaseReturn::join('product_purchase_returns', 'product_purchase_returns.purchase_return_id', 'purchase_returns.id')
                ->where('product_purchase_returns.product_id', $item->id)
                ->when(isset($data['start_date']), function ($query) use ($data) {
                    $query->whereDate('purchase_returns.created_at', '<', $data['start_date']);
                })
                ->sum('product_purchase_returns.qty');
            $sale_return = SaleReturn::join('product_sale_returns', 'product_sale_returns.sale_return_id', 'sale_returns.id')
                ->where('product_sale_returns.product_id', $item->id)
                ->when(isset($data['start_date']), function ($query) use ($data) {
                    $query->whereDate('sale_returns.created_at', '<', $data['start_date']);
                })
                ->sum('product_sale_returns.qty');
            $adjustment_plus = ProductAdjustment::where('product_id', $item->id)->where('type', 1)
                ->when(isset($data['start_date']), function ($query) use ($data) {
                    $query->whereDate('created_at', '<', $data['start_date']);
                })
                ->sum('qty');
            $adjustment_minus = ProductAdjustment::where('product_id', $item->id)->where('type', 0)
                ->when(isset($data['start_date']), function ($query) use ($data) {
                    $query->whereDate('created_at', '<', $data['start_date']);
                })
                ->sum('qty');
            return ($purchase - $sale + $sale_return - $purchase_return - $adjustment_minus + $adjustment_plus);
        } else {
            return 0;
        }
    }
    function closing_stock($item, $data)
    {
        $opening_stock = $this->Opening_stock($item, $data);
        $purchase = Purchase::join('product_purchases', 'product_purchases.purchase_id', 'purchases.id')
            ->where('product_purchases.product_id', $item->id)
            ->when(isset($data['start_date']), function ($query) use ($data) {
                $query->whereDate('purchases.created_at', '>=', $data['start_date']);
            })
            ->when(isset($data['end_date']), function ($query) use ($data) {
                $query->whereDate('purchases.created_at', '<=', $data['end_date']);
            })
            ->sum('product_purchases.qty');
        $sale = Sale::join('product_sales', 'product_sales.sale_id', 'sales.id')
            ->where('product_sales.product_id', $item->id)
            ->when(isset($data['start_date']), function ($query) use ($data) {
                $query->whereDate('sales.created_at', '>=', $data['start_date']);
            })
            ->when(isset($data['end_date']), function ($query) use ($data) {
                $query->whereDate('sales.created_at', '<=', $data['end_date']);
            })
            ->sum('product_sales.qty');
        $purchase_return = PurchaseReturn::join('product_purchase_returns', 'product_purchase_returns.purchase_return_id', 'purchase_returns.id')
            ->where('product_purchase_returns.product_id', $item->id)
            ->when(isset($data['start_date']), function ($query) use ($data) {
                $query->whereDate('purchase_returns.created_at', '>=', $data['start_date']);
            })
            ->when(isset($data['end_date']), function ($query) use ($data) {
                $query->whereDate('purchase_returns.created_at', '<=', $data['end_date']);
            })
            ->sum('product_purchase_returns.qty');
        $sale_return = SaleReturn::join('product_sale_returns', 'product_sale_returns.sale_return_id', 'sale_returns.id')
            ->where('product_sale_returns.product_id', $item->id)
            ->when(isset($data['start_date']), function ($query) use ($data) {
                $query->whereDate('sale_returns.created_at', '>=', $data['start_date']);
            })
            ->when(isset($data['end_date']), function ($query) use ($data) {
                $query->whereDate('sale_returns.created_at', '<=', $data['end_date']);
            })
            ->sum('product_sale_returns.qty');
        $adjustment_plus = ProductAdjustment::where('product_id', $item->id)->where('type', 1)
            ->when(isset($data['start_date']), function ($query) use ($data) {
                $query->whereDate('created_at', '>=', $data['start_date']);
            })
            ->when(isset($data['end_date']), function ($query) use ($data) {
                $query->whereDate('created_at', '<=', $data['end_date']);
            })
            ->sum('qty');
        $adjustment_minus = ProductAdjustment::where('product_id', $item->id)->where('type', 0)
            ->when(isset($data['start_date']), function ($query) use ($data) {
                $query->whereDate('created_at', '>=', $data['start_date']);
            })
            ->when(isset($data['end_date']), function ($query) use ($data) {
                $query->whereDate('created_at', '<=', $data['end_date']);
            })
            ->sum('qty');

        $closing_stock = ($opening_stock + $purchase - $sale + $sale_return - $purchase_return + $adjustment_plus - $adjustment_minus);
        return $closing_stock;
    }
    public function productReport($data)
    {
        $products = Product::all();
        return DataTables::of($products)
            ->addColumn('opening_stock', function ($item) use ($data) {
                $opening_stock = $this->Opening_stock($item, $data);
                return $opening_stock;
            })
            ->addColumn('purchase', function ($item) use ($data) {
                $purchase = Purchase::join('product_purchases', 'product_purchases.purchase_id', 'purchases.id')
                    ->where('product_purchases.product_id', $item->id)
                    ->when(isset($data['start_date']), function ($query) use ($data) {
                        $query->whereDate('purchases.created_at', '>=', $data['start_date']);
                    })
                    ->when(isset($data['end_date']), function ($query) use ($data) {
                        $query->whereDate('purchases.created_at', '<=', $data['end_date']);
                    })
                    ->sum('product_purchases.qty');
                return $purchase;
            })
            ->addColumn('sale', function ($item) use ($data) {
                $sale = Sale::join('product_sales', 'product_sales.sale_id', 'sales.id')
                    ->where('product_sales.product_id', $item->id)
                    ->when(isset($data['start_date']), function ($query) use ($data) {
                        $query->whereDate('sales.created_at', '>=', $data['start_date']);
                    })
                    ->when(isset($data['end_date']), function ($query) use ($data) {
                        $query->whereDate('sales.created_at', '<=', $data['end_date']);
                    })
                    ->sum('product_sales.qty');
                return $sale;
            })
            ->addColumn('purchase_return', function ($item) use ($data) {
                $purchase_return = PurchaseReturn::join('product_purchase_returns', 'product_purchase_returns.purchase_return_id', 'purchase_returns.id')
                    ->where('product_purchase_returns.product_id', $item->id)
                    ->when(isset($data['start_date']), function ($query) use ($data) {
                        $query->whereDate('purchase_returns.created_at', '>=', $data['start_date']);
                    })
                    ->when(isset($data['end_date']), function ($query) use ($data) {
                        $query->whereDate('purchase_returns.created_at', '<=', $data['end_date']);
                    })
                    ->sum('product_purchase_returns.qty');
                return $purchase_return;
            })
            ->addColumn('sale_return', function ($item) use ($data) {
                $sale_return = SaleReturn::join('product_sale_returns', 'product_sale_returns.sale_return_id', 'sale_returns.id')
                    ->where('product_sale_returns.product_id', $item->id)
                    ->when(isset($data['start_date']), function ($query) use ($data) {
                        $query->whereDate('sale_returns.created_at', '>=', $data['start_date']);
                    })
                    ->when(isset($data['end_date']), function ($query) use ($data) {
                        $query->whereDate('sale_returns.created_at', '<=', $data['end_date']);
                    })
                    ->sum('product_sale_returns.qty');
                return $sale_return;
            })
            ->addColumn('adjustment_plus', function ($item) use ($data) {
                $adjustment_plus = ProductAdjustment::where('product_id', $item->id)->where('type', 1)
                    ->when(isset($data['start_date']), function ($query) use ($data) {
                        $query->whereDate('created_at', '>=', $data['start_date']);
                    })
                    ->when(isset($data['end_date']), function ($query) use ($data) {
                        $query->whereDate('created_at', '<=', $data['end_date']);
                    })
                    ->sum('qty');
                return $adjustment_plus;
            })
            ->addColumn('adjustment_minus', function ($item) use ($data) {
                $adjustment_minus = ProductAdjustment::where('product_id', $item->id)->where('type', 0)
                    ->when(isset($data['start_date']), function ($query) use ($data) {
                        $query->whereDate('created_at', '>=', $data['start_date']);
                    })
                    ->when(isset($data['end_date']), function ($query) use ($data) {
                        $query->whereDate('created_at', '<=', $data['end_date']);
                    })
                    ->sum('qty');
                return $adjustment_minus;
            })
            ->addColumn('closing_stock', function ($item) use ($data) {
                return $this->closing_stock($item, $data);
            })
            ->addColumn('stock_price', function ($item) use ($data) {
                return ($this->closing_stock($item, $data) * $item->price);
            })
            ->make(true);
    }
    public function saleReport($data)
    {
        $start_date = $data['start_date'] ?? date('Y-m-d');
        $end_date = $data['end_date'] ?? date('Y-m-d');


        $sales = Sale::leftJoin('sale_payments', 'sales.id', '=', 'sale_payments.sale_id')
            ->select(
                DB::raw('DATE(sales.created_at) as sale_date'),
                DB::raw('SUM(sales.grand_total) as total_sales'),
            )
            ->whereRaw('DATE(sales.created_at) >= ?', [$start_date])
            ->whereRaw('DATE(sales.created_at) <= ?', [$end_date])
            ->groupBy(DB::raw('DATE(sales.created_at)'))
            ->orderBy('sale_date', 'asc')
            ->get();

        return DataTables::of($sales)
            ->addColumn('date', function ($item) {
                return \Carbon\Carbon::parse($item->sale_date)->format('d-M-Y');
            })
            ->addColumn('sale', function ($item) {
                return $item->total_sales;
            })
            ->addColumn('paid', function ($item) {
                $paid = SalePayment::join('sales', 'sales.id', 'sale_payments.sale_id')->where('sales.created_at', $item->sale_date)->sum('sale_payments.amount');
                return $paid;
            })
            ->addColumn('due', function ($item) {
                $paid = SalePayment::join('sales', 'sales.id', 'sale_payments.sale_id')->where('sales.created_at', $item->sale_date)->sum('sale_payments.amount');
                return $item->total_sales - $paid;
            })
            ->make(true);
    }
    public function purchaseReport($data)
    {
        $start_date = $data['start_date'] ?? date('Y-m-d');
        $end_date = $data['end_date'] ?? date('Y-m-d');

        $purchases = Purchase::select(
            DB::raw('DATE(purchases.created_at) as purchase_date'),
            DB::raw('SUM(purchases.grand_total) as total_purchases'),
        )
            ->whereRaw('DATE(purchases.created_at) >= ?', [$start_date])
            ->whereRaw('DATE(purchases.created_at) <= ?', [$end_date])
            ->groupBy(DB::raw('DATE(purchases.created_at)'))
            ->orderBy('purchase_date', 'asc')
            ->get();

        return DataTables::of($purchases)
            ->addColumn('date', function ($item) {
                return \Carbon\Carbon::parse($item->purchase_date)->format('d-M-Y');
            })
            ->addColumn('purchase', function ($item) {
                return $item->total_purchases;
            })
            ->addColumn('paid', function ($item) {
                $paid = PurchasePayment::join('purchases', 'purchases.id', 'purchase_payments.purchase_id')->where('purchases.created_at', $item->purchase_date)->sum('purchase_payments.amount');
                return $paid;
            })
            ->addColumn('due', function ($item) {
                $paid = PurchasePayment::join('purchases', 'purchases.id', 'purchase_payments.purchase_id')->where('purchases.created_at', $item->purchase_date)->sum('purchase_payments.amount');
                return $item->total_purchases - $paid;
            })
            ->make(true);
    }
    public function incomeReport($data)
    {
        $start_date = $data['start_date'] ?? date('Y-m-d');
        $end_date = $data['end_date'] ?? date('Y-m-d');

        $incomes = Income::select('created_at', 'user_id', 'amount')
            ->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)
            ->orderBy('created_at', 'asc')
            ->get();

        return DataTables::of($incomes)
            ->addColumn('date', function ($item) {
                return \Carbon\Carbon::parse($item->created_at)->format('d-M-Y');
            })
            ->addColumn('user', function ($item) {
                return $item->user->name ?? null;
            })
            ->addColumn('amount', function ($item) {
                return $item->amount ?? null;
            })
            ->make(true);
    }
    public function expenseReport($data)
    {
        $start_date = $data['start_date'] ?? date('Y-m-d');
        $end_date = $data['end_date'] ?? date('Y-m-d');

        $expenses = Expense::select('created_at', 'user_id', 'amount')
            ->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)
            ->orderBy('created_at', 'asc')
            ->get();

        return DataTables::of($expenses)
            ->addColumn('date', function ($item) {
                return \Carbon\Carbon::parse($item->created_at)->format('d-M-Y');
            })
            ->addColumn('user', function ($item) {
                return $item->user->name ?? null;
            })
            ->addColumn('amount', function ($item) {
                return $item->amount ?? null;
            })
            ->make(true);
    }
}