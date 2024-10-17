<?php

namespace App\Http\Controllers;

use App\Models\Invest;
use App\Service\InvestService;
use Illuminate\Http\Request;

class InvestController extends Controller
{
    public function __construct()
    {
        $this->baseService = new InvestService;
    }
    public function index()
    {

        $item = $this->baseService->Index();
        $columns = Invest::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.invest.index', compact('columns'));
    }
    public function trash()
    {

        $item = $this->baseService->Trash();
        $columns = Invest::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.invest.trash', compact('columns'));
    }

    public function store(Request $request)
    {
        if ($request->id == null) {
            $request->validate([
                'amount' => 'required',
            ]);
        }
        $data = $request->all();
        $message = $this->baseService->create($data);
        return redirect()->route('invest.index')->with($message);
    }
    function edit($id)
    {
        $invest = Invest::findOrFail($id);
        $item = $this->baseService->Index();
        $columns = Invest::$columns;
        if (request()->ajax()) {
            return $item;
        }
        return view('pages.invest.index', compact('columns', 'invest'));
    }
    function delete($id)
    {
        Invest::findOrFail($id)->delete();
        return redirect()->route('invest.index')->with('success', 'Invest Deleted Successfully');
    }
    function restore($id)
    {
        $invest = Invest::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('invest.index')->with('success', 'Invest Restored Successfully');
    }
    function pdelete($id)
    {
        Invest::withTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('invest.trash')->with('success', 'Invest Permanently Deleted Successfully');
    }
}
