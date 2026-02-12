<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::paginate(10); // ✅ bukan get()

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = config('permissions.list');
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'permissions' => 'nullable|array'
        ]);

        Role::create([
            'name' => $request->name,
            'permissions' => $request->permissions ?? []
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Role berhasil dibuat!');
    }

    public function edit(Role $role)
    {
        $permissions = config('permissions.list');
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required',
            'permissions' => 'nullable|array'
        ]);

        $role->update([
            'name' => $request->name,
            'permissions' => $request->permissions ?? []
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Role berhasil diupdate!');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role berhasil dihapus!');
    }
}
