<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\ProductSale;
use App\Models\Sale;
use App\Models\VoucherSetting;
use App\Service\SaleService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->baseService = new SaleService;
    }
    public function index(Request $request)
    {
        if (!userHasPermission('sale-index')) {
            return view('404');
        }

        $sales = Sale::query()->orderBy('sales.id', 'desc');
        if ($request->ajax()) {

            $sales->when($request->payment_status == -1, function ($q) {
                $q->where('payment_status', false);
            });

            $sales->when($request->payment_status == 1, function ($q) {
                $q->where('payment_status', true);
            });

            return DataTables::of($sales)
                ->addColumn('date', function ($item) {
                    return $item->created_at->format('d-M-Y') ?? 'N/A';
                })
                ->addColumn('customer', function ($item) {
                    return $item->customer->name ?? 'N/A';
                })
                ->addColumn('paid_amount', function ($item) {
                    return $item->paid;
                })
                ->addColumn('total_qty', function ($item) {
                    return $item->total_qty ?? 0;
                })
                ->addColumn('total_discount', function ($item) {
                    return $item->discount ?? 0;
                })
                ->addColumn('due', function ($item) {
                    return $item->grand_total - $item->paid;
                })
                ->addColumn('payment_status', function ($item) {
                    return $item->grand_total - $item->paid == 0
                        ? '<span class="badge badge-info">Paid</span>'
                        : '<span class="badge badge-danger">Due</span>';
                })
                ->addColumn('action', fn($item) => view('pages.sale.action', compact('item'))->render())
                ->rawColumns(['payment_status', 'action'])
                ->make(true);
        }
        return view('pages.sale.index');
    }
    public function trash(Request $request)
    {
        if (!userHasPermission('sale-advance')) {
            return view('404');
        }
        $sales = Sale::onlyTrashed()->orderBy('sales.id', 'desc');
        if ($request->ajax()) {
            return DataTables::of($sales)
                ->addColumn('date', function ($item) {
                    return $item->created_at->format('d-M-Y') ?? 'N/A';
                })
                ->addColumn('customer', function ($item) {
                    return $item->customer->name ?? 'N/A';
                })
                ->addColumn('paid_amount', function ($item) {
                    return $item->paid;
                })
                ->addColumn('total_qty', function ($item) {
                    return $item->total_qty ?? 0;
                })
                ->addColumn('total_discount', function ($item) {
                    return $item->discount ?? 0;
                })
                ->addColumn('due', function ($item) {
                    return $item->grand_total - $item->paid;
                })
                ->addColumn('payment_status', function ($item) {
                    return $item->grand_total - $item->paid == 0
                        ? '<span class="badge badge-info">Paid</span>'
                        : '<span class="badge badge-danger">Due</span>';
                })
                ->addColumn('action', fn($item) => view('pages.sale.taction', compact('item'))->render())
                ->rawColumns(['payment_status', 'action'])
                ->make(true);
        }
        return view('pages.sale.trash');
    }
    public function create()
    {
        if (!userHasPermission('sale-store')) {
            return view('404');
        }
        $customers = Customer::get();
        return view('pages.sale.create', compact('customers'));
    }
    public function getProduct(Request $request)
    {
        $search = $request->search;
        $response = $this->baseService->productSearch($search);
        return response()->json($response);
    }
    public function store(Request $request)
    {
        if (!userHasPermission('sale-store')) {
            return view('404');
        }
        $request->validate([
            'customer' => 'required',
        ]);
        $data = $request->all();
        $message = $this->baseService->create($data);
        session(['sale_id' => $message->id]);
        return redirect()->back()->with('success', 'Sale created successfully!');
    }
    function edit($id)
    {
        if (!userHasPermission('sale-update')) {
            return view('404');
        }
        $sale = Sale::findOrFail($id);
        $customers = Customer::get();
        return view('pages.sale.edit', compact('customers', 'sale'));
    }
    public function update(Request $request, $id)
    {
        if (!userHasPermission('sale-update')) {
            return view('404');
        }
        $request->validate([
            'customer' => 'required',
        ]);
        $data = $request->all();
        $message = $this->baseService->update($data, $id);
        return redirect()->route('sale.index')->with($message);
    }
    function delete($id)
    {
        if (!userHasPermission('sale-delete')) {
            return view('404');
        }
        $this->baseService->delete($id);
        return redirect()->route('sale.index')->with('success', 'Sale Deleted Successfully');
    }
    function restore($id)
    {
        if (!userHasPermission('sale-advance')) {
            return view('404');
        }
        Sale::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('sale.index')->with('success', 'Sale Restored Successfully');
    }
    function pdelete($id)
    {
        if (!userHasPermission('sale-advance')) {
            return view('404');
        }
        Sale::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('sale.trash')->with('success', 'Sale Permanently Deleted Successfully');
    }
    function invoice($id)
    {
        if (!userHasPermission('sale-advance')) {
            return view('404');
        }
        $sale = Sale::findOrFail($id);
        $voucher = VoucherSetting::findOrFail(1);
        if ($voucher->pos == true) {
            return view('pages.sale.pos_invoice', compact('sale'));
        } else {
            return view('pages.sale.invoice', compact('sale'));
        }
    }
    function show($id)
    {
        if (!userHasPermission('sale-advance')) {
            return view('404');
        }
        $sale = Sale::with('customer')->where('id', $id)->first();
        $items = ProductSale::where('sale_id', $id)
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
                'sale' => $sale,
                'items' => $items
            ]);
        };
    }
    function dueamount($id)
    {
        $sale = Sale::findOrFail($id);
        return $sale;
    }
}
