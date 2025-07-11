<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RolePermissionController extends Controller
{
    /**
     * ===== ROLES MANAGEMENT =====
     */

    /**
     * Display a listing of roles
     */
    public function rolesIndex(Request $request)
    {
        $query = Role::with('permissions');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $roles = $query->orderBy('name')->paginate(15);
        $permissions = Permission::orderBy('name')->get();

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    /**
     * Show the form for creating a new role
     */
    public function rolesCreate()
    {
        $permissions = Permission::orderBy('name')->get();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role
     */
    public function rolesStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Assign permissions to role
        if ($request->filled('permissions')) {
            foreach ($request->permissions as $permissionId) {
                $permission = Permission::find($permissionId);
                if ($permission) {
                    $role->givePermission($permission, auth()->id());
                }
            }
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Show the form for editing a role
     */
    public function rolesEdit(Role $role)
    {
        $role->load('permissions');
        $permissions = Permission::orderBy('name')->get();
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified role
     */
    public function rolesUpdate(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($role->id)],
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $role->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Sync permissions
        $role->permissions()->detach();
        if ($request->filled('permissions')) {
            foreach ($request->permissions as $permissionId) {
                $permission = Permission::find($permissionId);
                if ($permission) {
                    $role->givePermission($permission, auth()->id());
                }
            }
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified role
     */
    public function rolesDestroy(Role $role)
    {
        // Check if role is being used by users
        $userCount = $role->users()->count();
        if ($userCount > 0) {
            return redirect()->back()
                ->with('error', "Cannot delete role. It is assigned to {$userCount} user(s).");
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    // PERMISSIONS MANAGEMENT

    /**
     * Display a listing of permissions
     */
    public function permissionsIndex(Request $request)
    {
        $query = Permission::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $permissions = $query->orderBy('name')->paginate(20);

        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new permission
     */
    public function permissionsCreate()
    {
        return view('admin.permissions.create');
    }

    /**
     * Store a newly created permission
     */
    public function permissionsStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:permissions',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Permission::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Show the form for editing a permission
     */
    public function permissionsEdit(Permission $permission)
    {
        $categories = Permission::distinct()->pluck('category')->filter()->sort();
        $modules = Permission::distinct()->pluck('module')->filter()->sort();
        return view('admin.permissions.edit', compact('permission', 'categories', 'modules'));
    }

    /**
     * Update the specified permission
     */
    public function permissionsUpdate(Request $request, Permission $permission)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions')->ignore($permission->id)],
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'module' => 'required|string|max:100',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $permission->update([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'module' => $request->module,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified permission
     */
    public function permissionsDestroy(Permission $permission)
    {
        // Check if permission is being used by roles
        $roleCount = $permission->roles()->count();
        if ($roleCount > 0) {
            return redirect()->back()
                ->with('error', "Cannot delete permission. It is assigned to {$roleCount} role(s).");
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }

    /**
     * Assign permission to role (AJAX)
     */
    public function assignPermissionToRole(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        $role = Role::findOrFail($request->role_id);
        $permission = Permission::findOrFail($request->permission_id);

        $role->givePermission($permission, auth()->id());

        return response()->json([
            'success' => true,
            'message' => "Permission '{$permission->name}' assigned to role '{$role->name}'"
        ]);
    }

    /**
     * Remove permission from role (AJAX)
     */
    public function removePermissionFromRole(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        $role = Role::findOrFail($request->role_id);
        $permission = Permission::findOrFail($request->permission_id);

        $role->removePermission($permission);

        return response()->json([
            'success' => true,
            'message' => "Permission '{$permission->name}' removed from role '{$role->name}'"
        ]);
    }

    /**
     * Initialize default permissions
     */
    public function initializePermissions()
    {
        $defaultPermissions = [
            // User Management
            ['name' => 'View Users', 'category' => 'User Management', 'module' => 'users', 'description' => 'View list of users'],
            ['name' => 'Create Users', 'category' => 'User Management', 'module' => 'users', 'description' => 'Create new users'],
            ['name' => 'Edit Users', 'category' => 'User Management', 'module' => 'users', 'description' => 'Edit existing users'],
            ['name' => 'Delete Users', 'category' => 'User Management', 'module' => 'users', 'description' => 'Delete users'],
            ['name' => 'Manage User Roles', 'category' => 'User Management', 'module' => 'users', 'description' => 'Assign/remove roles from users'],

            // Role Management
            ['name' => 'View Roles', 'category' => 'Role Management', 'module' => 'roles', 'description' => 'View list of roles'],
            ['name' => 'Create Roles', 'category' => 'Role Management', 'module' => 'roles', 'description' => 'Create new roles'],
            ['name' => 'Edit Roles', 'category' => 'Role Management', 'module' => 'roles', 'description' => 'Edit existing roles'],
            ['name' => 'Delete Roles', 'category' => 'Role Management', 'module' => 'roles', 'description' => 'Delete roles'],

            // Permission Management
            ['name' => 'View Permissions', 'category' => 'Permission Management', 'module' => 'permissions', 'description' => 'View list of permissions'],
            ['name' => 'Create Permissions', 'category' => 'Permission Management', 'module' => 'permissions', 'description' => 'Create new permissions'],
            ['name' => 'Edit Permissions', 'category' => 'Permission Management', 'module' => 'permissions', 'description' => 'Edit existing permissions'],
            ['name' => 'Delete Permissions', 'category' => 'Permission Management', 'module' => 'permissions', 'description' => 'Delete permissions'],

            // Teacher Management
            ['name' => 'View Teachers', 'category' => 'Teacher Management', 'module' => 'teachers', 'description' => 'View list of teachers'],
            ['name' => 'Create Teachers', 'category' => 'Teacher Management', 'module' => 'teachers', 'description' => 'Create new teachers'],
            ['name' => 'Edit Teachers', 'category' => 'Teacher Management', 'module' => 'teachers', 'description' => 'Edit teacher profiles'],
            ['name' => 'Delete Teachers', 'category' => 'Teacher Management', 'module' => 'teachers', 'description' => 'Delete teachers'],
            ['name' => 'Verify Teachers', 'category' => 'Teacher Management', 'module' => 'teachers', 'description' => 'Verify teacher accounts'],

            // Student Management
            ['name' => 'View Students', 'category' => 'Student Management', 'module' => 'students', 'description' => 'View list of students'],
            ['name' => 'Create Students', 'category' => 'Student Management', 'module' => 'students', 'description' => 'Create new students'],
            ['name' => 'Edit Students', 'category' => 'Student Management', 'module' => 'students', 'description' => 'Edit student profiles'],
            ['name' => 'Delete Students', 'category' => 'Student Management', 'module' => 'students', 'description' => 'Delete students'],

            // Institute Management
            ['name' => 'View Institutes', 'category' => 'Institute Management', 'module' => 'institutes', 'description' => 'View list of institutes'],
            ['name' => 'Create Institutes', 'category' => 'Institute Management', 'module' => 'institutes', 'description' => 'Create new institutes'],
            ['name' => 'Edit Institutes', 'category' => 'Institute Management', 'module' => 'institutes', 'description' => 'Edit institute profiles'],
            ['name' => 'Delete Institutes', 'category' => 'Institute Management', 'module' => 'institutes', 'description' => 'Delete institutes'],
            ['name' => 'Verify Institutes', 'category' => 'Institute Management', 'module' => 'institutes', 'description' => 'Verify institute accounts'],

            // Subject Management
            ['name' => 'View Subjects', 'category' => 'Subject Management', 'module' => 'subjects', 'description' => 'View list of subjects'],
            ['name' => 'Create Subjects', 'category' => 'Subject Management', 'module' => 'subjects', 'description' => 'Create new subjects'],
            ['name' => 'Edit Subjects', 'category' => 'Subject Management', 'module' => 'subjects', 'description' => 'Edit subjects'],
            ['name' => 'Delete Subjects', 'category' => 'Subject Management', 'module' => 'subjects', 'description' => 'Delete subjects'],

            // System Management
            ['name' => 'System Settings', 'category' => 'System Management', 'module' => 'system', 'description' => 'Manage system settings'],
            ['name' => 'View Logs', 'category' => 'System Management', 'module' => 'system', 'description' => 'View system logs'],
            ['name' => 'Backup Database', 'category' => 'System Management', 'module' => 'system', 'description' => 'Create database backups'],
        ];

        $created = 0;
        foreach ($defaultPermissions as $permData) {
            if (!Permission::where('name', $permData['name'])->exists()) {
                Permission::create($permData);
                $created++;
            }
        }

        return redirect()->back()
            ->with('success', "Initialized {$created} default permissions.");
    }
}
