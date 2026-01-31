<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    // public function index()
    // {
    //     $users = User::with('role')->get();
    //     $roles = Role::all();
    //     return view('users.index', compact('users', 'roles'));
    // }
     public function index(Request $request)
    {
        $query = User::with('role');
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('role', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Role filter
        if ($request->has('role') && !empty($request->role)) {
            $query->where('role_id', $request->role);
        }
        
        // Sort functionality
        $sortColumn = $request->get('sort', 'id');
        $sortDirection = $request->get('direction', 'asc');
        
        // Validate sort column
        $validColumns = ['id', 'name', 'email', 'created_at'];
        if (!in_array($sortColumn, $validColumns)) {
            $sortColumn = 'id';
        }
        
        $query->orderBy($sortColumn, $sortDirection);
        
        // Pagination
        $users = $query->paginate(10)->withQueryString();
        
        $roles = Role::all();
        
        return view('users.index', compact('users', 'roles'));
    }

        // Add export methods
    public function exportPDF(Request $request)
    {
        $users = $this->getFilteredUsers($request);
        
        $pdf = Pdf::loadView('exports.users-pdf', [
            'users' => $users,
            'title' => 'Users Report'
        ]);
        
        return $pdf->download('users_' . date('Y-m-d') . '.pdf');
    }
    
    public function exportExcel(Request $request)
    {
        $users = $this->getFilteredUsers($request);
        
        return Excel::download(new UsersExport($users), 'users_' . date('Y-m-d') . '.xlsx');
    }
    
    public function exportCSV(Request $request)
    {
        $users = $this->getFilteredUsers($request);
        
        return Excel::download(new UsersExport($users), 'users_' . date('Y-m-d') . '.csv', \Maatwebsite\Excel\Excel::CSV);
    }
    
    private function getFilteredUsers(Request $request)
    {
        $query = User::with('role');
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('role', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->has('role') && !empty($request->role)) {
            $query->where('role_id', $request->role);
        }
        
        return $query->get();
    }


    public function create()
    {
        $roles = Role::where('id', '!=', 1)->get();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);



        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    }

    // Add these new methods for edit, update, and delete
    public function edit(User $user)
    {
        // Prevent editing system admin if you want (optional)
        if ($user->role_id == 1 && $user->id != auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Cannot edit other system admins!');
        }
        
        $roles = Role::where('id', '!=', 1)->get();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // Prevent updating system admin if you want (optional)
        if ($user->role_id == 1 && $user->id != auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Cannot update other system admins!');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id == auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete yourself!');
        }

        // Prevent deleting system admin (optional)
        if ($user->role_id == 1) {
            return redirect()->route('admin.users.index')->with('error', 'Cannot delete system admin!');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }

    // public function updateRole(Request $request, $id)
    // {
    //     $request->validate([
    //         'role_id' => ['required', 'exists:roles,id'],
    //     ]);

    //     $user = User::findOrFail($id);
    //     $user->update(['role_id' => $request->role_id]);

    //     if ($request->ajax()) {
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'User role updated successfully!',
    //             'role_name' => $user->role->name,
    //             'role_id' => $user->role_id
    //         ]);
    //     }

    //     return redirect()->route('users.index')->with('success', 'User role updated successfully!');
    // }
}