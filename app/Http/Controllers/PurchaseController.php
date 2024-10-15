<?php

namespace App\Http\Controllers;

use App\Models\ProductPurchase;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Service\PurchaseService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->baseService = new PurchaseService;
    }
    public function index(Request $request)
    {
        $sales = Purchase::query()->orderBy('purchases.id', 'desc');

        if ($request->ajax()) {

            if ($request->payment_status == -1) {
                $sales->where('payment_status', false);
            }

            $sales->when($request->payment_status == 1, function ($q) {
                $q->where('payment_status', true);
            });

            return DataTables::of($sales)
                ->addColumn('date', function ($item) {
                    return $item->created_at->format('d-M-Y') ?? 'N/A';
                })
                ->addColumn('supplier', function ($item) {
                    return $item->supplier->name ?? 'N/A';
                })
                ->addColumn('paid_amount', function ($item) {
                    return $item->paid;
                })
                ->addColumn('total_qty', function ($item) {
                    return $item->total_qty ?? 0;
                })
                ->addColumn('due', function ($item) {
                    return $item->grand_total - $item->paid;
                })
                ->addColumn('payment_status', function ($item) {
                    return $item->grand_total - $item->paid == 0
                        ? '<span class="badge badge-info">Paid</span>'
                        : '<span class="badge badge-danger">Due</span>';
                })
                ->addColumn('action', fn($item) => view('pages.purchase.action', compact('item'))->render())
                ->rawColumns(['payment_status', 'action'])
                ->make(true);
        }
        return view('pages.purchase.index');
    }
    public function trash(Request $request)
    {
        $purchases = Purchase::onlyTrashed()->orderBy('purchases.id', 'desc');

        if ($request->ajax()) {

            return DataTables::of($purchases)
                ->addColumn('date', function ($item) {
                    return $item->created_at->format('d-M-Y') ?? 'N/A';
                })
                ->addColumn('supplier', function ($item) {
                    return $item->supplier->name ?? 'N/A';
                })
                ->addColumn('paid_amount', function ($item) {
                    return $item->paid;
                })
                ->addColumn('total_qty', function ($item) {
                    return $item->total_qty ?? 0;
                })
                ->addColumn('due', function ($item) {
                    return $item->grand_total - $item->paid;
                })
                ->addColumn('payment_status', function ($item) {
                    return $item->grand_total - $item->paid == 0
                        ? '<span class="badge badge-info">Paid</span>'
                        : '<span class="badge badge-danger">Due</span>';
                })
                ->addColumn('action', fn($item) => view('pages.purchase.taction', compact('item'))->render())
                ->rawColumns(['payment_status', 'action'])
                ->make(true);
        }
        return view('pages.purchase.trash');
    }
    public function create()
    {
        $suppliers = Supplier::get();
        return view('pages.purchase.create', compact('suppliers'));
    }
    public function getProduct(Request $request)
    {
        $search = $request->search;
        $response = $this->baseService->productSearch($search);
        return response()->json($response);
    }
    public function store(Request $request)
    {
        $request->validate([
            'supplier' => 'required',
        ]);
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('purchase.index')->with($message);
    }
    function edit($id)
    {
        $purchase = Purchase::findOrFail($id);
        $suppliers = Supplier::active();
        return view('pages.purchase.edit', compact('suppliers', 'purchase'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier' => 'required',
        ]);
        $data = $request->all();
        $message = $this->baseService->update($data, $id);
        return redirect()->route('purchase.index')->with($message);
    }
    function delete($id)
    {
        $this->baseService->delete($id);
        return redirect()->route('purchase.index')->with('success', 'Purchase Deleted Successfully');
    }
    function restore($id)
    {
        $this->baseService->restore($id);
        return redirect()->route('purchase.index')->with('success', 'Purchase Restored Successfully');
    }
    function pdelete($id)
    {
        Purchase::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('purchase.trash')->with('success', 'Purchase Permanently Deleted Successfully');
    }
    function show($id)
    {
        $purchase = Purchase::with('supplier')->where('id', $id)->first();
        $items = ProductPurchase::where('purchase_id', $id)
            ->with(['product' => function ($query) {
                $query->with([
                    'category' => function ($q) {
                        $q->select('id', 'name');
                    }
                ]);
            }])
            ->get();
        $paid = $purchase->paid;
        // send for sale details modal
        if (request()->ajax()) {
            return response()->json([
                'purchase' => $purchase,
                'items' => $items,
                'paid' => $paid,
            ]);
        };
    }
    function dueamount($id)
    {
        $purchase = Purchase::findOrFail($id);
        $paid = $purchase->paid; // This will call the getPaidAttribute method

        return response()->json([
            'purchase' => $purchase,
            'paid' => $paid,
        ]);
    }
}
