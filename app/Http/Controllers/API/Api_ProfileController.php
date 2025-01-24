<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Api_ProfileController extends Controller
{
    /**
     * Show the user's profile.
     */
    public function show(Request $request)
    {
        $user = Auth::user();
        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    /**
     * Update the user's profile.
     */
    public function updateName(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->name = $request->name;
        $user->save();

        return response()->json(['message' => 'Name updated successfully']);
    }

    /**
     * Update the user's email.
     */
    public function updateEmail(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->email = $request->email;
        $user->save();

        return response()->json(['message' => 'Email updated successfully']);
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password updated successfully']);
    }
}
