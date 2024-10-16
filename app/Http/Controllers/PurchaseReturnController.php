<?php

namespace App\Http\Controllers;

use App\Models\ProductPurchaseReturn;
use App\Models\PurchaseReturn;
use App\Models\Supplier;
use App\Service\PurchaseReturnService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PurchaseReturnController extends Controller
{
    public function __construct()
    {
        $this->baseService = new PurchaseReturnService;
    }
    public function index(Request $request)
    {
        $sales = PurchaseReturn::query()->orderBy('purchase_returns.id', 'desc');

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
                ->addColumn('qty', function ($item) {
                    return $item->qty ?? 0;
                })
                ->addColumn('action', fn($item) => view('pages.purchase-return.action', compact('item'))->render())
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.purchase-return.index');
    }
    public function create()
    {
        $suppliers = Supplier::get();
        return view('pages.purchase-return.create', compact('suppliers'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'supplier' => 'required',
        ]);
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('purchase.return.index')->with($message);
    }
    function edit($id)
    {
        $purchase_return = PurchaseReturn::findOrFail($id);
        $suppliers = Supplier::get();
        return view('pages.purchase-return.edit', compact('suppliers', 'purchase_return'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier' => 'required',
            'voucher_no' => 'required',
        ]);
        $data = $request->all();
        $message = $this->baseService->update($data, $id);
        return redirect()->route('purchase.return.index')->with($message);
    }
    function delete($id)
    {
        PurchaseReturn::findOrFail($id)->delete();
        return redirect()->route('purchase.return.index')->with('success', 'Purchase Return Deleted Successfully');
    }
    function show($id)
    {
        $purchase_return = PurchaseReturn::with('supplier')->where('id', $id)->first();
        $items = ProductPurchaseReturn::where('purchase_return_id', $id)
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
                'purchase_return' => $purchase_return,
                'items' => $items
            ]);
        };
    }
}
