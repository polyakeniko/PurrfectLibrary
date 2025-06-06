<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PartnerController extends Controller
{
    public function showForm()
    {
        $user = auth()->user();
        $partner = Partner::where('user_id', $user->id)->first();
        if ($partner) {
            return redirect()->route('partners.success')->with('api_key', $partner->api_key);
        }
        return view('partners.form');
    }

    public function register(Request $request)
    {
        $user = auth()->user();
        $partner = Partner::where('user_id', $user->id)->first();
        if ($partner) {
            return redirect()->route('partners.success')->with('api_key', $partner->api_key);
        }

        $request->validate([
            'website' => 'required|url',
        ]);

        $partner = Partner::create([
            'user_id' => $user->id,
            'api_key' => Str::random(32),
            'is_active' => true,
        ]);

        return redirect()->route('partners.success')->with('api_key', $partner->api_key);
    }

    public function success()
    {
        $user = auth()->user();
        $partner = Partner::where('user_id', $user->id)->first();
        $apiKey = session('api_key') ?? ($partner ? $partner->api_key : null);
        return view('partners.success', compact('apiKey'));
    }

    public function regenerateApiKey()
    {
        $user = auth()->user();
        $partner = Partner::where('user_id', $user->id)->first();

        if (!$partner) {
            return redirect()->route('partners.form')->with('error', 'You are not a partner yet.');
        }

        $partner->update(['api_key' => Str::random(32)]);

        return redirect()->route('partners.success')->with('api_key', $partner->api_key)->with('status', 'API key regenerated successfully.');
    }
}
