<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index()
    {
        $setting = Setting::find(1);
        return view('pages.setting', compact('setting'));;
    }
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'contact' => 'required',
        ]);

        $data = $request->except('logo'); // Use parentheses () instead of square brackets []
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $name = time() . '.' . $file->getClientOriginalExtension();
            $file->move('upload/', $name);
            $data['logo'] = $name;
        }

        Setting::findOrFail(1)->update($data);
        return back()->with('success', 'Site Setting Updated Successfully');
    }
}
