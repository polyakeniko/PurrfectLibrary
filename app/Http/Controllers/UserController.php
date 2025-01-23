<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show()
    {
        $loans = Loan::where('user_id', auth()->id())
            //->whereNull('returned_date')
            ->get();

        return view('user.borrowed', compact('loans'));
    }
    public function getAllUsers(): JsonResponse
    {
        $users = User::all();
        return response()->json($users);
    }
    public function index()
    {
        $members = User::where('role', 'user')->orderBy('name')->get();
        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'status' => 'active',
        ]);

        return redirect()->route('members.index')->with('success', 'Member created successfully.');
    }

    public function edit(User $user)
    {
        return view('members.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        return redirect()->route('members.index')->with('success', 'Member updated successfully.');
    }

    public function block(User $user)
    {
        $user->update(['status' => 'banned']);
        return redirect()->route('members.index')->with('success', 'Member blocked.');
    }

    public function unblock(User $user)
    {
        $user->update(['status' => 'active']);
        return redirect()->route('members.index')->with('success', 'Member unblocked.');
    }
}
