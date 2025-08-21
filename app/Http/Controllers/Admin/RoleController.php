<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::latest()->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all(); // or paginate/group as needed
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array|nullable',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(['name' => $request->name]);

        // ✅ Convert permission IDs to names before syncing
        if ($request->has('permissions')) {
            $permissionNames = Permission::whereIn('id', $request->permissions)->pluck('name');
            $role->syncPermissions($permissionNames);
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::findOrFail($id);
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
            'permissions' => 'array|nullable',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->save();

        // ✅ Convert permission IDs to names before syncing
        if ($request->has('permissions')) {
            $permissionNames = Permission::whereIn('id', $request->permissions)->pluck('name');
            $role->syncPermissions($permissionNames);
        } else {
            // If no permissions selected, remove all
            $role->syncPermissions([]);
        }

        return redirect()->route('roles.index')->with('success', 'Role updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);

        // Check if the role is not the default role
        if ($role->name === 'Admin') {
            return redirect()->route('roles.index')->with('error', 'Cannot delete the Admin role.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
