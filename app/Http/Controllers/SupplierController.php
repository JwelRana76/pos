<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Division;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Models\Supplier;
use App\Service\SupplierService;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->baseService = new SupplierService;
    }
    public function index()
    {
        if (!userHasPermission('supplier-index')) {
            return view('404');
        }
        $item = $this->baseService->Index();
        $columns = Supplier::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.supplier.index', compact('columns'));
    }
    public function trash()
    {
        if (!userHasPermission('supplier-advance')) {
            return view('404');
        }
        $item = $this->baseService->Trash();
        $columns = Supplier::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.supplier.trash', compact('columns'));
    }
    public function create()
    {
        if (!userHasPermission('supplier-store')) {
            return view('404');
        }
        $districts = District::get();
        $divisions = Division::get();
        return view('pages.supplier.create', compact('districts', 'divisions'));
    }
    public function store(Request $request)
    {
        if (!userHasPermission('supplier-store')) {
            return view('404');
        }
        $request->validate([
            'name' => 'required',
            'contact' => 'required',
            'district' => 'required',
        ]);
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('supplier.index')->with($message);
    }
    public function supplierstore(Request $request)
    {
        if (!userHasPermission('supplier-store')) {
            return view('404');
        }
        $request->validate([
            'name' => 'required',
            'contact' => 'required',
            'district' => 'required',
        ]);
        $data = $request->all();
        $message = $this->baseService->create($data);
        $suppliers = Supplier::get();
        return $suppliers;
    }
    function edit($id)
    {
        if (!userHasPermission('supplier-update')) {
            return view('404');
        }
        $supplier = Supplier::findOrFail($id);
        $districts = District::get();
        $divisions = Division::get();
        return view('pages.supplier.edit', compact('supplier', 'districts', 'divisions'));
    }
    public function update(Request $request, $id)
    {
        if (!userHasPermission('supplier-update')) {
            return view('404');
        }
        $request->validate([
            'name' => 'required',
            'contact' => 'required',
            'district' => 'required',
        ]);
        $data = $request->all();
        $message = $this->baseService->update($data, $id);
        return redirect()->route('supplier.index')->with($message);
    }
    function delete($id)
    {
        if (!userHasPermission('supplier-delete')) {
            return view('404');
        }
        Supplier::findOrFail($id)->delete();
        return redirect()->route('supplier.index')->with('success', 'Supplier Deleted Successfully');
    }
    function restore($id)
    {
        Supplier::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('supplier.index')->with('success', 'Supplier Restored Successfully');
    }
    function pdelete($id)
    {
        if (!userHasPermission('supplier-advance')) {
            return view('404');
        }
        Supplier::withTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('supplier.index')->with('success', 'Supplier Permanently Deleted Successfully');
    }

    function Import(Request $request)
    {
        if (!userHasPermission('supplier-advance')) {
            return view('404');
        }
        $request->validate([
            'supplier_file' => 'required|file|mimes:csv,txt',
        ]);
        $message = $this->baseService->Import($request->all());
        return redirect()->route('supplier.index')->with($message);
    }
    function purchaseDetails($id)
    {
        $message = $this->baseService->purchaseDetails($id);
        return $message;
    }
    function purchaseDue($id)
    {
        $purchase = Purchase::findOrFail($id);
        return $purchase->grand_total - $purchase->paid;
    }
    function payment(Request $request)
    {
        $message = $this->baseService->payment($request->all());
        return redirect()->back()->with($message);
    }
    function paymentDetails($id)
    {
        $message = $this->baseService->paymentDetails($id);
        return $message;
    }
    function paymentDelete($id)
    {
        $payment = PurchasePayment::findOrFail($id);
        $purchase = Purchase::findOrFail($payment->purchase_id);
        $purchase->payment_status = false;
        $purchase->save();
        $payment->delete();
        return redirect()->back()->with('success', 'Payment Deleted Successfully');
    }
}
