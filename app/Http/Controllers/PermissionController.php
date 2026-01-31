<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        $permissionGroups = $this->permissionService->getPermissionGroups();
        
        return view('permissions.index', compact('roles', 'permissions', 'permissionGroups'));
    }

    public function updateRolePermissions(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);
        
        $role = Role::findOrFail($request->role_id);
        $role->permissions()->sync($request->permissions ?? []);
        
        return response()->json([
            'success' => true, 
            'message' => 'Permissions updated successfully.',
            'role' => $role->load('permissions')
        ]);
    }

    /**
     * Show form to assign permissions to specific user
     */
    public function assignUserPermissions(User $user)
    {
        $permissions = Permission::all();
        $userPermissions = $user->role->permissions->pluck('id')->toArray();
        $permissionGroups = $this->permissionService->getPermissionGroups();
        
        return view('permissions.assign-user', compact('user', 'permissions', 'userPermissions', 'permissionGroups'));
    }

    /**
     * Store user-specific permissions
     */
    public function storeUserPermissions(Request $request, User $user)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);
        
        $user->role->permissions()->sync($request->permissions ?? []);
        
        return redirect()->route('users.index')
            ->with('success', 'Permissions assigned successfully to ' . $user->name);
    }
}