<?php

namespace App\Http\Controllers;

use App\Service\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->baseService = new ReportService;
    }

    public function productReport(Request $request)
    {
        $data = $request->all();
        $item = $this->baseService->productReport($data);
        $columns =
            [
                ['name' => 'name', 'data' => 'name'],
                ['name' => 'opening_stock', 'data' => 'opening_stock'],
                ['name' => 'purchase', 'data' => 'purchase'],
                ['name' => 'sale', 'data' => 'sale'],
                ['name' => 'purchase_return', 'data' => 'purchase_return'],
                ['name' => 'sale_return', 'data' => 'sale_return'],
                ['name' => 'adjustment_plus', 'data' => 'adjustment_plus'],
                ['name' => 'adjustment_minus', 'data' => 'adjustment_minus'],
                ['name' => 'closing_stock', 'data' => 'closing_stock'],
                ['name' => 'stock_price', 'data' => 'stock_price'],
            ];
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.report.product', compact('columns'));
    }
    public function saleReport(Request $request)
    {
        $data = $request->all();
        $item = $this->baseService->saleReport($data);
        $columns =
            [
                ['name' => 'date', 'data' => 'date'],
                ['name' => 'sale', 'data' => 'sale'],
                ['name' => 'paid', 'data' => 'paid'],
                ['name' => 'due', 'data' => 'due'],
            ];
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.report.sale', compact('columns'));
    }
    public function purchaseReport(Request $request)
    {
        $data = $request->all();
        $item = $this->baseService->purchaseReport($data);
        $columns =
            [
                ['name' => 'date', 'data' => 'date'],
                ['name' => 'purchase', 'data' => 'purchase'],
                ['name' => 'paid', 'data' => 'paid'],
                ['name' => 'due', 'data' => 'due'],
            ];
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.report.purchase', compact('columns'));
    }
    public function incomeReport(Request $request)
    {
        $data = $request->all();
        $item = $this->baseService->incomeReport($data);
        $columns =
            [
                ['name' => 'date', 'data' => 'date'],
                ['name' => 'user', 'data' => 'user'],
                ['name' => 'amount', 'data' => 'amount'],
            ];
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.report.income', compact('columns'));
    }
    public function expenseReport(Request $request)
    {
        $data = $request->all();
        $item = $this->baseService->expenseReport($data);
        $columns =
            [
                ['name' => 'date', 'data' => 'date'],
                ['name' => 'user', 'data' => 'user'],
                ['name' => 'amount', 'data' => 'amount'],
            ];
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.report.expense', compact('columns'));
    }
}
