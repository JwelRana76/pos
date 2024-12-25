<?php

namespace App\Http\Controllers;

use App\Models\VoucherSetting;
use Illuminate\Http\Request;

class VoucherSettingController extends Controller
{
    public function index()
    {
        $voucher = VoucherSetting::find(1);
        return view('pages.voucher_setting', compact('voucher'));;
    }
    public function update(Request $request)
    {

        $data['pos'] = $request->pos == 'on' ? true : false;
        $data['expense'] = $request->expense == 'on' ? true : false;
        $data['purchase'] = $request->purchase == 'on' ? true : false;
        $data['salary'] = $request->salary == 'on' ? true : false;

        VoucherSetting::findOrFail(1)->update($data);
        return back()->with('success', 'Voucher Setting Updated Successfully');
    }
}
