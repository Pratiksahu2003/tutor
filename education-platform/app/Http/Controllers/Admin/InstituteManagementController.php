<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Institute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class InstituteManagementController extends Controller
{
    /**
     * Display a listing of institutes
     */
    public function index(Request $request)
    {
        $query = Institute::with('user');

        // Filter by verification status
        if ($request->filled('verified')) {
            $query->where('verified', $request->verified === 'verified');
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Filter by established year
        if ($request->filled('established_year')) {
            $query->where('established_year', $request->established_year);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('institute_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%");
            });
        }

        $institutes = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get filter options
        $cities = Institute::distinct()->pluck('city')->filter()->sort();
        $establishedYears = Institute::distinct()->pluck('established_year')->filter()->sort()->reverse();

        return view('admin.institutes.index', compact('institutes', 'cities', 'establishedYears'));
    }

    /**
     * Show the form for creating a new institute
     */
    public function create()
    {
        return view('admin.institutes.create');
    }

    /**
     * Store a newly created institute
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'institute_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'contact_person' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'description' => 'nullable|string',
            'registration_number' => 'nullable|string|max:100|unique:institutes',
            'website' => 'nullable|url',
            'established_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'total_students' => 'nullable|integer|min:0',
            'verified' => 'boolean',
            'is_active' => 'boolean',
            'facilities' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create user for institute
        $user = User::create([
            'name' => $request->contact_person,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'institute',
            'phone' => $request->contact_phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'country' => 'India',
            'is_active' => $request->boolean('is_active', true),
            'email_verified_at' => now(),
        ]);

        // Create institute
        Institute::create([
            'user_id' => $user->id,
            'institute_name' => $request->institute_name,
            'description' => $request->description,
            'registration_number' => $request->registration_number,
            'website' => $request->website,
            'contact_person' => $request->contact_person,
            'contact_phone' => $request->contact_phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'established_year' => $request->established_year,
            'total_students' => $request->total_students ?? 0,
            'rating' => 0,
            'verified' => $request->boolean('verified', false),
            'facilities' => json_encode($request->facilities ?? []),
            'gallery_images' => json_encode([]),
        ]);

        return redirect()->route('admin.institutes.index')
            ->with('success', 'Institute created successfully.');
    }

    /**
     * Display the specified institute
     */
    public function show(Institute $institute)
    {
        $institute->load('user');
        return view('admin.institutes.show', compact('institute'));
    }

    /**
     * Show the form for editing the specified institute
     */
    public function edit(Institute $institute)
    {
        $institute->load('user');
        return view('admin.institutes.edit', compact('institute'));
    }

    /**
     * Update the specified institute
     */
    public function update(Request $request, Institute $institute)
    {
        $validator = Validator::make($request->all(), [
            'institute_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $institute->user_id,
            'password' => 'nullable|string|min:8|confirmed',
            'contact_person' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'description' => 'nullable|string',
            'registration_number' => 'nullable|string|max:100|unique:institutes,registration_number,' . $institute->id,
            'website' => 'nullable|url',
            'established_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'total_students' => 'nullable|integer|min:0',
            'verified' => 'boolean',
            'is_active' => 'boolean',
            'facilities' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update user
        $updateUserData = [
            'name' => $request->contact_person,
            'email' => $request->email,
            'phone' => $request->contact_phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->filled('password')) {
            $updateUserData['password'] = Hash::make($request->password);
        }

        $institute->user->update($updateUserData);

        // Update institute
        $institute->update([
            'institute_name' => $request->institute_name,
            'description' => $request->description,
            'registration_number' => $request->registration_number,
            'website' => $request->website,
            'contact_person' => $request->contact_person,
            'contact_phone' => $request->contact_phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'established_year' => $request->established_year,
            'total_students' => $request->total_students ?? 0,
            'verified' => $request->boolean('verified', false),
            'facilities' => json_encode($request->facilities ?? []),
        ]);

        return redirect()->route('admin.institutes.index')
            ->with('success', 'Institute updated successfully.');
    }

    /**
     * Remove the specified institute
     */
    public function destroy(Institute $institute)
    {
        // Also delete the associated user
        $institute->user->delete();
        
        return redirect()->route('admin.institutes.index')
            ->with('success', 'Institute deleted successfully.');
    }

    /**
     * Verify institute
     */
    public function verify(Institute $institute)
    {
        $institute->update(['verified' => true]);

        return redirect()->back()
            ->with('success', 'Institute verified successfully.');
    }

    /**
     * Unverify institute
     */
    public function unverify(Institute $institute)
    {
        $institute->update(['verified' => false]);

        return redirect()->back()
            ->with('success', 'Institute verification removed.');
    }

    /**
     * Toggle institute status
     */
    public function toggleStatus(Institute $institute)
    {
        $institute->user->update(['is_active' => !$institute->user->is_active]);

        $status = $institute->user->is_active ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', "Institute {$status} successfully.");
    }

    /**
     * Get institute statistics
     */
    public function statistics()
    {
        $stats = [
            'total_institutes' => Institute::count(),
            'verified_institutes' => Institute::where('verified', true)->count(),
            'unverified_institutes' => Institute::where('verified', false)->count(),
            'active_institutes' => Institute::whereHas('user', function ($q) {
                $q->where('is_active', true);
            })->count(),
            'total_students_enrolled' => Institute::sum('total_students'),
            'average_rating' => round(Institute::where('rating', '>', 0)->avg('rating'), 2),
        ];

        return response()->json($stats);
    }

    /**
     * Bulk verify institutes
     */
    public function bulkVerify(Request $request)
    {
        $request->validate([
            'institute_ids' => 'required|array',
            'institute_ids.*' => 'exists:institutes,id',
        ]);

        $updated = Institute::whereIn('id', $request->institute_ids)
            ->update(['verified' => true]);

        return redirect()->back()
            ->with('success', "Verified {$updated} institute(s) successfully.");
    }

    /**
     * Bulk activate/deactivate institutes
     */
    public function bulkToggleStatus(Request $request)
    {
        $request->validate([
            'institute_ids' => 'required|array',
            'institute_ids.*' => 'exists:institutes,id',
            'action' => 'required|in:activate,deactivate',
        ]);

        $isActive = $request->action === 'activate';
        $institutes = Institute::whereIn('id', $request->institute_ids)->with('user')->get();
        
        $updated = 0;
        foreach ($institutes as $institute) {
            $institute->user->update(['is_active' => $isActive]);
            $updated++;
        }

        $action = $isActive ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', "Successfully {$action} {$updated} institute(s).");
    }

    /**
     * Update institute rating
     */
    public function updateRating(Request $request, Institute $institute)
    {
        $request->validate([
            'rating' => 'required|numeric|min:0|max:5',
        ]);

        $institute->update(['rating' => $request->rating]);

        return redirect()->back()
            ->with('success', 'Institute rating updated successfully.');
    }

    /**
     * Update student count
     */
    public function updateStudentCount(Request $request, Institute $institute)
    {
        $request->validate([
            'total_students' => 'required|integer|min:0',
        ]);

        $institute->update(['total_students' => $request->total_students]);

        return redirect()->back()
            ->with('success', 'Student count updated successfully.');
    }
} 