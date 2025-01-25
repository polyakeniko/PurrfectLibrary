<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Api_AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'status' => '422 => Unprocessable Entity'], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));


        return response()->json(['message' => 'Registration successful, please check your email for verification link', 'status' => '201 => Created'], 201);
    }

    /**
     * Login a user.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'status' => '422 => Unprocessable Entity'], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials', 'status' => '401 => Unauthorized'], 401);
        }

        if ($user->status == 'banned') {
            return response()->json(['message' => 'Account is banned', 'status' => '403 => Forbidden'], 403);
        }

        if (is_null($user->email_verified_at)) {
            return response()->json(['message' => 'Email address is not verified', 'status' => '403 => Forbidden'], 403);
        }

        // Check for existing non-expired tokens
        $existingToken = $user->tokens()->where('expires_at', '>', Carbon::now())->first();

        if ($existingToken) {
            $token = $existingToken->plainTextToken;
        } else {
            // Create a new token with 2 hours expiration
            $token = $user->createToken('auth_token', ['*'], Carbon::now()->addHours(2))->plainTextToken;
        }

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'status' => '200 => OK'
        ], 200);
    }
    /**
     * Logout a user.
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logout successful', 'status' => '200 => OK'], 200);
    }
}
