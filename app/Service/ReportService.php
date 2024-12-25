<?php

namespace App\Service;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Invest;
use App\Models\Product;
use App\Models\ProductAdjustment;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Models\PurchaseReturn;
use App\Models\SalaryPayment;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\SaleReturn;
use DateTime;
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
    public function dateWiseSaleReport($data)
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
    public function dateWisePurchaseReport($data)
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
    public function saleReport($data)
    {
        $start_date = $data['start_date'] ?? date('Y-m-d');
        $end_date = $data['end_date'] ?? date('Y-m-d');


        $sales = Sale::where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)
            ->select('voucher_no', 'id', 'created_at', 'grand_total')->orderBy('created_at', 'asc')->get();

        return DataTables::of($sales)
            ->addColumn('date', function ($item) {
                return $item->created_at->format('d-M-Y');
            })
            ->addColumn('invoice_no', function ($item) {
                return $item->voucher_no;
            })
            ->addColumn('amount', function ($item) {
                return $item->grand_total;
            })
            ->addColumn('paid', function ($item) {
                $paid = SalePayment::where('sale_id', $item->id)->sum('amount');
                return $paid;
            })
            ->addColumn('due', function ($item) {
                $paid = SalePayment::where('sale_id', $item->id)->sum('amount');
                return $item->grand_total - $paid;
            })
            ->make(true);
    }
    public function purchaseReport($data)
    {
        $start_date = $data['start_date'] ?? date('Y-m-d');
        $end_date = $data['end_date'] ?? date('Y-m-d');


        $sales = Purchase::where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)
            ->select('voucher_no', 'id', 'created_at', 'grand_total')->orderBy('created_at', 'asc')->get();

        return DataTables::of($sales)
            ->addColumn('date', function ($item) {
                return $item->created_at->format('d-M-Y');
            })
            ->addColumn('invoice_no', function ($item) {
                return $item->voucher_no;
            })
            ->addColumn('amount', function ($item) {
                return $item->grand_total;
            })
            ->addColumn('paid', function ($item) {
                $paid = PurchasePayment::where('purchase_id', $item->id)->sum('amount');
                return $paid;
            })
            ->addColumn('due', function ($item) {
                $paid = PurchasePayment::where('purchase_id', $item->id)->sum('amount');
                return $item->grand_total - $paid;
            })
            ->make(true);
    }
    function customerLedgerReport($data)
    {
        $start_date = new DateTime($data['start_date'] ?? date('Y-m-d')); // Convert to DateTime
        $end_date = new DateTime($data['end_date'] ?? date('Y-m-d'));     // Convert to DateTime

        $ledger = [];
        $balance = 0;
        $backup_date = clone $start_date;
        $back_date = $backup_date->modify('-1 day');

        $sale = Sale::whereDate('created_at', '<', $start_date)
            ->when(isset($data['customer_id']), function ($query) use ($data) {
                $query->where('customer_id', $data['customer_id']);
            })
            ->sum('grand_total');

        $paid = SalePayment::whereDate('created_at', '<', $start_date)
            ->when(isset($data['customer_id']), function ($query) use ($data) {
                $query->where('customer_id', $data['customer_id']);
            })
            ->sum('amount');

        $balance += ($sale - $paid);

        // Push data into the array
        $ledger[] = [
            'date' => $back_date->format('d-M-Y'),
            'sale' => $sale,
            'paid' => $paid,
            'balance' => $balance,
        ];
        for ($date = $start_date; $date <= $end_date; $date->modify('+1 day')) {
            $sale = Sale::whereDate('created_at', $date)
                ->when(isset($data['customer_id']), function ($query) use ($data) {
                    $query->where('customer_id', $data['customer_id']);
                })
                ->sum('grand_total');

            $paid = SalePayment::whereDate('created_at', $date)
                ->when(isset($data['customer_id']), function ($query) use ($data) {
                    $query->where('customer_id', $data['customer_id']);
                })
                ->sum('amount');

            if ($sale > 0 || $paid > 0) {
                $balance += ($sale - $paid);

                // Push data into the array
                $ledger[] = [
                    'date' => $date->format('d-M-Y'),
                    'sale' => $sale,
                    'paid' => $paid,
                    'balance' => $balance,
                ];
            }
        }

        return DataTables::of($ledger)
            ->addColumn('date', function ($item) {
                return $item['date'];
            })
            ->addColumn('sale', function ($item) {
                return $item['sale'];
            })
            ->addColumn('paid', function ($item) {
                return $item['paid'];
            })
            ->addColumn('balance', function ($item) {
                return $item['balance'];
            })
            ->make(true);
    }
    function supplierLedgerReport($data)
    {
        $start_date = new DateTime($data['start_date'] ?? date('Y-m-d')); // Convert to DateTime
        $end_date = new DateTime($data['end_date'] ?? date('Y-m-d'));     // Convert to DateTime

        $ledger = [];
        $balance = 0;
        $backup_date = clone $start_date;
        $back_date = $backup_date->modify('-1 day');
        $purchase = Purchase::whereDate('created_at', '<', $start_date)
            ->when(isset($data['supplier_id']), function ($query) use ($data) {
                $query->where('supplier_id', $data['supplier_id']);
            })
            ->sum('grand_total');

        $paid = PurchasePayment::whereDate('created_at', '<', $start_date)
            ->when(isset($data['supplier_id']), function ($query) use ($data) {
                $query->where('supplier_id', $data['supplier_id']);
            })
            ->sum('amount');

        $balance += ($purchase - $paid);
        $ledger[] = [
            'date' => $back_date->format('d-M-Y'),
            'purchase' => $purchase,
            'paid' => $paid,
            'balance' => $balance,
        ];
        for ($date = $start_date; $date <= $end_date; $date->modify('+1 day')) {
            $purchase = Purchase::whereDate('created_at', $date)
                ->when(isset($data['supplier_id']), function ($query) use ($data) {
                    $query->where('supplier_id', $data['supplier_id']);
                })
                ->sum('grand_total');
            $paid = PurchasePayment::whereDate('created_at', $date)
                ->when(isset($data['supplier_id']), function ($query) use ($data) {
                    $query->where('supplier_id', $data['supplier_id']);
                })
                ->sum('amount');

            if ($purchase > 0 || $paid > 0) {
                $balance += ($purchase - $paid);

                // Push data into the array
                $ledger[] = [
                    'date' => $date->format('d-M-Y'),
                    'purchase' => $purchase,
                    'paid' => $paid,
                    'balance' => $balance,
                ];
            }
        }

        return DataTables::of($ledger)
            ->addColumn('date', function ($item) {
                return $item['date'];
            })
            ->addColumn('purchase', function ($item) {
                return $item['purchase'];
            })
            ->addColumn('paid', function ($item) {
                return $item['paid'];
            })
            ->addColumn('balance', function ($item) {
                return $item['balance'];
            })
            ->make(true);
    }
    function bank($data)
    {
        $start_date = new DateTime($data['start_date'] ?? date('Y-m-d')); // Convert to DateTime
        $end_date = new DateTime($data['end_date'] ?? date('Y-m-d'));     // Convert to DateTime

        $ledger = [];
        $balance = 0;
        $backup_date = clone $start_date;
        $back_date = $backup_date->modify('-1 day');
        $purchase_payment = PurchasePayment::whereDate('created_at', '<', $start_date)
            ->where('account_id', null)
            ->when(isset($data['bank']), function ($query) use ($data) {
                $query->where('bank_id', $data['bank']);
            })
            ->sum('amount');

        $sale_payment = SalePayment::whereDate('created_at', '<', $start_date)
            ->where('account_id', null)
            ->when(isset($data['bank']), function ($query) use ($data) {
                $query->where('bank_id', $data['bank']);
            })
            ->sum('amount');
        $invest = Invest::whereDate('created_at', '<', $start_date)
            ->where('account_id', null)
            ->when(isset($data['bank']), function ($query) use ($data) {
                $query->where('bank_id', $data['bank']);
            })
            ->sum('amount');
        $salary_paid = SalaryPayment::whereDate('created_at', '<', $start_date)
            ->where('account_id', null)
            ->when(isset($data['bank']), function ($query) use ($data) {
                $query->where('bank_id', $data['bank']);
            })
            ->sum('amount');
        $credit = $invest + $sale_payment;
        $debit = $purchase_payment + $salary_paid;
        $balance += ($credit - $debit);
        $ledger[] = [
            'date' => $back_date->format('d-M-Y'),
            'credit' => $credit,
            'debit' => $debit,
            'balance' => $balance,
        ];
        for ($date = $start_date; $date <= $end_date; $date->modify('+1 day')) {
            $purchase_payment = PurchasePayment::whereDate('created_at', $date)
                ->where('account_id', null)
                ->when(isset($data['bank']), function ($query) use ($data) {
                    $query->where('bank_id', $data['bank']);
                })
                ->sum('amount');
            $sale_payment = SalePayment::whereDate('created_at', $date)
                ->where('account_id', null)
                ->when(isset($data['bank']), function ($query) use ($data) {
                    $query->where('bank_id', $data['bank']);
                })
                ->sum('amount');
            $invest = Invest::whereDate('created_at', $date)
                ->where('account_id', null)
                ->when(isset($data['bank']), function ($query) use ($data) {
                    $query->where('bank_id', $data['bank']);
                })
                ->sum('amount');
            $salary_paid = SalaryPayment::whereDate('created_at', $date)
                ->where('account_id', null)
                ->when(isset($data['bank']), function ($query) use ($data) {
                    $query->where('bank_id', $data['bank']);
                })
                ->sum('amount');
            $credit = $invest + $sale_payment;
            $debit = $purchase_payment + $salary_paid;
            $balance += ($credit - $debit);
            if ($credit > 0 || $debit > 0) {
                $ledger[] = [
                    'date' => $date->format('d-M-Y'),
                    'credit' => $credit,
                    'debit' => $debit,
                    'balance' => $balance,
                ];
            }
        }

        return DataTables::of($ledger)
            ->addColumn('date', function ($item) {
                return $item['date'];
            })
            ->addColumn('credit', function ($item) {
                return $item['credit'];
            })
            ->addColumn('debit', function ($item) {
                return $item['debit'];
            })
            ->addColumn('balance', function ($item) {
                return $item['balance'];
            })
            ->make(true);
    }
    function account($data)
    {
        $start_date = new DateTime($data['start_date'] ?? date('Y-m-d')); // Convert to DateTime
        $end_date = new DateTime($data['end_date'] ?? date('Y-m-d'));     // Convert to DateTime

        $ledger = [];
        $balance = 0;
        $backup_date = clone $start_date;
        $back_date = $backup_date->modify('-1 day');
        $purchase_payment = PurchasePayment::whereDate('created_at', '<', $start_date)
            ->where('bank_id', null)
            ->when(isset($data['account']), function ($query) use ($data) {
                $query->where('account_id', $data['account']);
            })
            ->sum('amount');

        $sale_payment = SalePayment::whereDate('created_at', '<', $start_date)
            ->where('bank_id', null)
            ->when(isset($data['account']), function ($query) use ($data) {
                $query->where('account_id', $data['account']);
            })
            ->sum('amount');
        $invest = Invest::whereDate('created_at', '<', $start_date)
            ->where('bank_id', null)
            ->when(isset($data['account']), function ($query) use ($data) {
                $query->where('account_id', $data['account']);
            })
            ->sum('amount');
        $salary_paid = SalaryPayment::whereDate('created_at', '<', $start_date)
            ->where('bank_id', null)
            ->when(isset($data['account']), function ($query) use ($data) {
                $query->where('account_id', $data['account']);
            })
            ->sum('amount');
        // dd($invest);
        $credit = $invest + $sale_payment;
        $debit = $purchase_payment + $salary_paid;
        $balance += ($credit - $debit);
        $ledger[] = [
            'date' => $back_date->format('d-M-Y'),
            'credit' => $credit,
            'debit' => $debit,
            'balance' => $balance,
        ];
        for ($date = $start_date; $date <= $end_date; $date->modify('+1 day')) {
            $purchase_payment = PurchasePayment::whereDate('created_at', $date)
                ->where('bank_id', null)
                ->when(isset($data['account']), function ($query) use ($data) {
                    $query->where('account_id', $data['account']);
                })
                ->sum('amount');
            $sale_payment = SalePayment::whereDate('created_at', $date)
                ->where('bank_id', null)
                ->when(isset($data['account']), function ($query) use ($data) {
                    $query->where('account_id', $data['account']);
                })
                ->sum('amount');
            $invest = Invest::whereDate('created_at', $date)
                ->where('bank_id', null)
                ->when(isset($data['account']), function ($query) use ($data) {
                    $query->where('account_id', $data['account']);
                })
                ->sum('amount');
            $salary_paid = SalaryPayment::whereDate('created_at', $date)
                ->where('bank_id', null)
                ->when(isset($data['account']), function ($query) use ($data) {
                    $query->where('account_id', $data['account']);
                })
                ->sum('amount');
            $credit = $invest + $sale_payment;
            $debit = $purchase_payment + $salary_paid;
            $balance += ($credit - $debit);
            if ($credit > 0 || $debit > 0) {
                $ledger[] = [
                    'date' => $date->format('d-M-Y'),
                    'credit' => $credit,
                    'debit' => $debit,
                    'balance' => $balance,
                ];
            }
        }

        return DataTables::of($ledger)
            ->addColumn('date', function ($item) {
                return $item['date'];
            })
            ->addColumn('credit', function ($item) {
                return $item['credit'];
            })
            ->addColumn('debit', function ($item) {
                return $item['debit'];
            })
            ->addColumn('balance', function ($item) {
                return $item['balance'];
            })
            ->make(true);
    }
}