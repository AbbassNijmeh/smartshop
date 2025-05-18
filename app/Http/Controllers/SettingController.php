<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $setting = Setting::first();
        return view('admin.settings', compact('setting'));
    }
    public function update(Request $request)
    {
        $valdiatedData = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'facebook' => 'required',
            'tiktok' => 'required',
            'instagram' => 'required',

        ]);
        $setting = Setting::first();
        $setting->update($request->all());
        return redirect()->back()->with('success', 'Setting updated successfully.');
    }
}
