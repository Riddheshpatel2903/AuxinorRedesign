<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        $settingsData = $settings->pluck('value', 'key')->toArray();
        
        return view('admin.settings.index', compact('settingsData'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings_files.*' => 'nullable|image|max:5120',
        ]);

        foreach ($request->settings as $key => $value) {
            Setting::set($key, $value);
        }

        if ($request->hasFile('settings_files')) {
            foreach ($request->file('settings_files') as $key => $file) {
                $path = $file->store('settings', 'public');
                Setting::set($key, Storage::url($path));
            }
        }

        cache()->forget('global_settings');

        return back()->with('success', 'Settings updated successfully.');
    }
}
