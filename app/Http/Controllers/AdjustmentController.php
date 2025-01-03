<?php

namespace App\Http\Controllers;

use App\Models\Adjustment;
use App\Models\ProductAdjustment;
use App\Service\AdjustmentService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdjustmentController extends Controller
{
    public function __construct()
    {
        $this->baseService = new AdjustmentService;
    }
    public function index(Request $request)
    {
        if (!userHasPermission('adjustment-index')) {
            return view('404');
        }
        $sale_returns = Adjustment::query()->orderBy('id', 'desc');

        if ($request->ajax()) {
            return DataTables::of($sale_returns)
                ->addColumn('date', function ($item) {
                    return $item->created_at->format('d-M-Y') ?? 'N/A';
                })
                ->addColumn('user', function ($item) {
                    return $item->user->name ?? 'N/A';
                })
                ->addColumn('qty', function ($item) {
                    return $item->qty ?? 0;
                })
                ->addColumn('action', fn($item) => view('pages.adjustment.action', compact('item'))->render())
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.adjustment.index');
    }
    public function create()
    {
        if (!userHasPermission('adjustment-index')) {
            return view('404');
        }
        return view('pages.adjustment.create');
    }
    public function store(Request $request)
    {
        if (!userHasPermission('adjustment-store')) {
            return view('404');
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('adjustment.index')->with($message);
    }
    function edit($id)
    {
        if (!userHasPermission('adjustment-update')) {
            return view('404');
        }
        $adjustment = Adjustment::findOrFail($id);
        return view('pages.adjustment.edit', compact('adjustment'));
    }
    public function update(Request $request, $id)
    {
        if (!userHasPermission('adjustment-update')) {
            return view('404');
        }
        $data = $request->all();
        $message = $this->baseService->update($data, $id);
        return redirect()->route('adjustment.index')->with($message);
    }
    function delete($id)
    {
        if (!userHasPermission('adjustment-delete')) {
            return view('404');
        }
        Adjustment::findOrFail($id)->delete();
        return redirect()->route('adjustment.index')->with('success', 'Adjustment Deleted Successfully');
    }
    function show($id)
    {
        if (!userHasPermission('adjustment-advance')) {
            return view('404');
        }
        $adjustment = Adjustment::with('user')->where('id', $id)->first();
        $items = ProductAdjustment::where('adjustment_id', $id)
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
                'adjustment' => $adjustment,
                'items' => $items
            ]);
        };
    }
}
