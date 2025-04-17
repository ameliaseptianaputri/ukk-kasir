<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
{
    $users = User::all();
    return view('admin.user.index', compact('users'));
}

public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
{
    try {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4',
            'role' => 'required|in:admin,petugas',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_seeded' => 0
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan');
    } catch (\Exception $e) {
        return redirect()->route('user.index')->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
    }
}


    // public function show(User $user)
    // {
    //     return view('users.show', compact('user'));
    // }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $id,
                'role' => 'required|in:admin,petugas',
                'password' => 'nullable|min:4', // Password opsional, minimal 4 karakter jika diisi
            ]);

            // Data yang akan diperbarui
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            return redirect()->route('user.index')->with('success', 'User berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->route('user.index')->with('error', 'Gagal memperbarui user: ' . $e->getMessage());
        }
    }

public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return redirect()->route('user.index')->with('success', 'User berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('user.index')->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }
}
