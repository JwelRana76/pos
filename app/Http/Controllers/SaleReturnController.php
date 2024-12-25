<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\ProductSaleReturn;
use App\Models\SaleReturn;
use App\Service\SaleReturnService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SaleReturnController extends Controller
{
    public function __construct()
    {
        $this->baseService = new SaleReturnService;
    }
    public function index(Request $request)
    {
        if (!userHasPermission('return-index')) {
            return view('404');
        }
        $sale_returns = SaleReturn::query()->orderBy('sale_returns.id', 'desc');

        if ($request->ajax()) {
            return DataTables::of($sale_returns)
                ->addColumn('date', function ($item) {
                    return $item->created_at->format('d-M-Y') ?? 'N/A';
                })
                ->addColumn('customer', function ($item) {
                    return $item->customer->name ?? 'N/A';
                })
                ->addColumn('qty', function ($item) {
                    return $item->qty ?? 0;
                })
                ->addColumn('action', fn($item) => view('pages.sale-return.action', compact('item'))->render())
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.sale-return.index');
    }
    public function create()
    {
        if (!userHasPermission('return-store')) {
            return view('404');
        }
        $customers = Customer::get();
        return view('pages.sale-return.create', compact('customers'));
    }
    public function store(Request $request)
    {
        if (!userHasPermission('return-store')) {
            return view('404');
        }
        $request->validate([
            'customer' => 'required',
        ]);
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('sale.return.index')->with($message);
    }
    function edit($id)
    {
        if (!userHasPermission('return-update')) {
            return view('404');
        }
        $sale_return = SaleReturn::findOrFail($id);
        $customers = Customer::get();
        return view('pages.sale-return.edit', compact('customers', 'sale_return'));
    }
    public function update(Request $request, $id)
    {
        if (!userHasPermission('return-update')) {
            return view('404');
        }
        $request->validate([
            'customer' => 'required',
            'voucher_no' => 'required',
        ]);
        $data = $request->all();
        $message = $this->baseService->update($data, $id);
        return redirect()->route('sale.return.index')->with($message);
    }
    function delete($id)
    {
        if (!userHasPermission('return-delete')) {
            return view('404');
        }
        SaleReturn::findOrFail($id)->delete();
        return redirect()->route('sale.return.index')->with('success', 'Sale Return Deleted Successfully');
    }
    function show($id)
    {
        $sale_return = SaleReturn::with('customer')->where('id', $id)->first();
        $items = ProductSaleReturn::where('sale_return_id', $id)
            ->with(['product' => function ($query) {
                $query->with([
                    'category' => function ($q) {
                        $q->select('id', 'name');
                    }
                ]);
            }])
            ->get();
        // send for sale details modal
        if (request()->ajax()) {
            return response()->json([
                'sale_return' => $sale_return,
                'items' => $items
            ]);
        };
    }
}
