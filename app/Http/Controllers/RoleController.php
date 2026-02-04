<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Display a listing of all roles
     */
    public function index()
    {
        $roles = Role::with('permissions', 'users')->paginate(10);
        $permissions = Permission::all();
        $permissionGroups = $this->permissionService->getPermissionGroups();
        
        return view('roles.index', compact('roles', 'permissions', 'permissionGroups'));
    }

    /**
     * Show the form for creating a new role
     */
    public function create()
    {
        $permissions = Permission::all();
        $permissionGroups = $this->permissionService->getPermissionGroups();

        return view('roles.create', compact('permissions', 'permissionGroups'));
    }

    /**
     * Store a newly created role in database
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'slug' => 'required|string|max:255|unique:roles,slug',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Create the role
        $role = Role::create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        // Attach permissions
        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully with permissions!');
    }

    /**
     * Show the form for editing the specified role
     */
    public function edit(Role $role)
    {
        // Prevent editing system-admin role
        if ($role->slug === 'system-admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Cannot edit the System Admin role.');
        }

        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        $permissionGroups = $this->permissionService->getPermissionGroups();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions', 'permissionGroups'));
    }

    /**
     * Update the specified role in database
     */
    public function update(Request $request, Role $role)
    {
        // Prevent updating system-admin role
        if ($role->slug === 'system-admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Cannot update the System Admin role.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'slug' => 'required|string|max:255|unique:roles,slug,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        // Sync permissions
        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully with permissions!');
    }

    /**
     * Remove the specified role from database
     */
    public function destroy(Role $role)
    {
        // Prevent deleting system-admin role
        if ($role->slug === 'system-admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Cannot delete the System Admin role.');
        }

        // Prevent deleting role if it has users
        if ($role->users()->exists()) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Cannot delete role with assigned users. Please reassign or delete users first.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully!');
    }
}
