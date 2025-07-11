<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\TeacherProfile;
use App\Models\StudentProfile;
use App\Models\Institute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    /**
     * Display a listing of all users
     */
    public function index(Request $request)
    {
        $query = User::with(['roles', 'teacherProfile', 'studentProfile', 'institute']);

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:student,teacher,institute,admin',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'country' => $request->country ?? 'India',
            'is_active' => $request->boolean('is_active', true),
            'email_verified_at' => now(),
        ]);

        // Assign additional roles if provided
        if ($request->filled('roles')) {
            $user->syncRoles($request->roles);
        }

        // Create role-specific profiles
        $this->createRoleSpecificProfile($user, $request);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $user->load(['roles', 'teacherProfile', 'studentProfile', 'institute']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load('roles');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:student,teacher,institute,admin',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'country' => $request->country ?? 'India',
            'is_active' => $request->boolean('is_active', true),
        ];

        // Update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        // Sync additional roles if provided
        if ($request->filled('roles')) {
            $user->syncRoles($request->roles);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy(User $user)
    {
        // Prevent deleting the current admin user
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle user status (active/inactive)
     */
    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', "User {$status} successfully.");
    }

    /**
     * Assign role to user
     */
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $role = Role::findOrFail($request->role_id);
        
        if ($user->assignRole($role)) {
            return redirect()->back()
                ->with('success', "Role '{$role->name}' assigned successfully.");
        }

        return redirect()->back()
            ->with('info', "User already has the '{$role->name}' role.");
    }

    /**
     * Remove role from user
     */
    public function removeRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $role = Role::findOrFail($request->role_id);
        
        if ($user->removeRole($role)) {
            return redirect()->back()
                ->with('success', "Role '{$role->name}' removed successfully.");
        }

        return redirect()->back()
            ->with('info', "User doesn't have the '{$role->name}' role.");
    }

    /**
     * Get users by role (AJAX endpoint)
     */
    public function getUsersByRole(Request $request)
    {
        $role = $request->get('role');
        $users = User::where('role', $role)
            ->where('is_active', true)
            ->select('id', 'name', 'email')
            ->get();

        return response()->json($users);
    }

    /**
     * Create role-specific profiles
     */
    private function createRoleSpecificProfile(User $user, Request $request)
    {
        switch ($user->role) {
            case 'teacher':
                if (!$user->teacherProfile) {
                    TeacherProfile::create([
                        'user_id' => $user->id,
                        'qualification' => $request->qualification ?? '',
                        'specialization' => $request->specialization ?? '',
                        'experience_years' => $request->experience_years ?? 0,
                        'hourly_rate' => $request->hourly_rate ?? 0,
                        'bio' => $request->bio ?? '',
                        'verified' => $request->boolean('verified', false),
                        'teaching_city' => $user->city,
                        'teaching_state' => $user->state,
                        'teaching_pincode' => $user->pincode,
                    ]);
                }
                break;

            case 'student':
                if (!$user->studentProfile) {
                    StudentProfile::create([
                        'user_id' => $user->id,
                        'current_class' => $request->current_class ?? '',
                        'school_name' => $request->school_name ?? '',
                        'board' => $request->board ?? 'CBSE',
                        'city' => $user->city,
                        'state' => $user->state,
                        'pincode' => $user->pincode,
                        'address' => $user->address,
                        'is_active' => true,
                        'profile_completed' => true,
                    ]);
                }
                break;

            case 'institute':
                if (!$user->institute) {
                    Institute::create([
                        'user_id' => $user->id,
                        'institute_name' => $request->institute_name ?? $user->name,
                        'description' => $request->institute_description ?? '',
                        'contact_person' => $user->name,
                        'contact_phone' => $user->phone,
                        'address' => $user->address,
                        'city' => $user->city,
                        'state' => $user->state,
                        'pincode' => $user->pincode,
                        'verified' => $request->boolean('verified', false),
                    ]);
                }
                break;
        }
    }
}
