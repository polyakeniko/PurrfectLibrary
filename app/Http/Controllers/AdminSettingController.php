<?php

namespace App\Http\Controllers;

use App\Models\AdminSetting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = AdminSetting::first();

        if (!$settings) {
            $settings = AdminSetting::create([
                'library_open_time' => '',
                'library_close_time' => '',
                'description' => '',
                'max_books_to_loan' => 0,
            ]);
        }
        $description = $settings ? $settings->description : '';

        return view('admin_settings.index', compact('settings', 'description'));
    }

    public function update(Request $request, AdminSetting $adminSetting)
    {
        $request->validate([
            'library_open_time' => 'required|string',
            'library_close_time' => 'required|string',
            'description' => 'nullable|string',
            'max_books_to_loan' => 'required|integer',
        ]);

        $adminSetting->update($request->all());

        return redirect()->route('admin_settings.index')->with('success', 'Settings updated successfully.');
    }

    public function getDescription()
    {
        $settings = AdminSetting::first();
        $description = $settings ? $settings->description : '';
        $library_open_time = $settings ? $settings->library_open_time : '';
        $library_close_time = $settings ? $settings->library_close_time : '';
        $max_books_to_loan = $settings ? $settings->max_books_to_loan : 0;

        return view('welcome', compact('description', 'library_open_time', 'library_close_time', 'max_books_to_loan'));
    }
}
