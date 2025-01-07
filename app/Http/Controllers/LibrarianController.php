<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LibrarianController extends Controller
{
    public function index()
    {
        $librarians = User::where('role', 'librarian')->get();
        return view('librarians.index', compact('librarians'));
    }

    public function create()
    {
        return view('librarians.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'librarian',
            'status' => 'active',
        ]);

        return redirect()->route('librarians.index')->with('success', 'Librarian created successfully.');
    }

    public function edit(User $librarian)
    {
        return view('librarians.edit', compact('librarian'));
    }

    public function update(Request $request, User $librarian)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $librarian->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $librarian->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $librarian->password,
        ]);

        return redirect()->route('librarians.index')->with('success', 'Librarian updated successfully.');
    }

    public function destroy(User $librarian)
    {
        try {
            $librarian->update(['status' => 'banned']);
            Log::info('Librarian status after update: ' . $librarian->status);
            return redirect()->route('librarians.index')->with('success', 'Librarian banned successfully.');
        } catch (\Exception $e) {
            Log::error('Error banning librarian: ' . $e->getMessage());
            return redirect()->route('librarians.index')->with('error', 'Failed to ban librarian.');
        }
    }

    public function activate(User $librarian)
    {
        $librarian->update(['status' => 'active']);
        return redirect()->route('librarians.index')->with('success', 'Librarian activated successfully.');
    }
}
