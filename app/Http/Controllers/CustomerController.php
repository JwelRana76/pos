<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\District;
use App\Models\Division;
use App\Service\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->baseService = new CustomerService;
    }
    public function index()
    {

        $item = $this->baseService->Index();
        $columns = Customer::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.customer.index', compact('columns'));
    }
    public function trash()
    {

        $item = $this->baseService->Trash();
        $columns = Customer::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.customer.trash', compact('columns'));
    }
    public function create()
    {

        $districts = District::get();
        $divisions = Division::get();
        return view('pages.customer.create', compact('districts', 'divisions'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'contact' => 'required',
            'district' => 'required',
        ]);
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('customer.index')->with($message);
    }
    function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $districts = District::get();
        $divisions = Division::get();
        return view('pages.customer.edit', compact('customer', 'districts', 'divisions'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'contact' => 'required',
            'district' => 'required',
        ]);
        $data = $request->all();
        $message = $this->baseService->update($data, $id);
        return redirect()->route('customer.index')->with($message);
    }
    function delete($id)
    {
        Customer::findOrFail($id)->delete();
        return redirect()->route('customer.index')->with('success', 'Customer Deleted Successfully');
    }
    function restore($id)
    {
        Customer::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('customer.index')->with('success', 'Customer Restored Successfully');
    }
    function pdelete($id)
    {
        Customer::withTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('customer.trash')->with('success', 'Customer Permanently Deleted Successfully');
    }

    function saleDetails($id)
    {
        $message = $this->baseService->saleDetails($id);
        return $message;
    }
    function saleDue($id)
    {
        $sale = Sale::findOrFail($id);
        return $sale->grand_total - $sale->paid;
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
        $payment = SalePayment::findOrFail($id);
        $sale = Sale::findOrFail($payment->sale_id);
        $sale->payment_status = false;
        $sale->save();
        $payment->delete();
        return redirect()->back()->with('success', 'Payment Deleted Successfully');
    }
}
