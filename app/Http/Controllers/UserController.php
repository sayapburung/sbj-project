<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Tampilkan daftar user dengan filter pencarian nama
     */
    public function index(Request $request)
    {
        $query = User::with('role');

        // Filter berdasarkan nama
        if ($request->search) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $users = $query->paginate(5)->appends($request->only('search'));

        return view('users.index', compact('users'));
    }

    /**
     * Tampilkan form tambah user
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Simpan user baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|exists:roles,id',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Hapus user
     */
    public function destroy($id)
        {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
        }

    public function toggle($id)
        {
            $user = User::findOrFail($id);
            $user->is_active = !$user->is_active;
            $user->save();

            return redirect()->route('users.index')->with('success', 'Status user berhasil diperbarui.');
        }

    public function resetPassword($id)
        {
            $user = User::findOrFail($id);
            $user->password = Hash::make('1234'); // password default
            $user->save();

            return redirect()->route('users.index')->with('success', 'Password user berhasil direset ke default.');
        }
}
