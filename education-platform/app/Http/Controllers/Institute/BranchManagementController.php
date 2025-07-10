<?php

namespace App\Http\Controllers\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Institute;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Models\Subject;

class BranchManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:institute']);
    }

    /**
     * Display branch management dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $institute = $user->institute;

        if (!$institute) {
            return redirect()->route('institute.profile')->with('error', 'Please complete your institute profile first.');
        }

        // Get all branches in hierarchy
        $allBranches = $this->getBranchHierarchy($institute);
        $stats = $this->getBranchStatistics($institute);

        return view('institute.branches.index', compact('institute', 'allBranches', 'stats'));
    }

    /**
     * Show create branch form
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $institute = $user->institute;

        if (!$institute) {
            return redirect()->route('institute.profile')->with('error', 'Institute profile required.');
        }

        $parentId = $request->get('parent_id', $institute->id);
        $parentBranch = Institute::find($parentId);

        // Verify user can manage this parent branch
        if (!$parentBranch || !$parentBranch->canBeManaged($user)) {
            return redirect()->back()->with('error', 'Unauthorized to create branch here.');
        }

        $subjects = Subject::active()->get();
        $branchManagers = User::byRole('teacher')->active()->get();

        return view('institute.branches.create', compact('institute', 'parentBranch', 'subjects', 'branchManagers'));
    }

    /**
     * Store a new branch
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $institute = $user->institute;

        if (!$institute) {
            return redirect()->route('institute.profile')->with('error', 'Institute profile required.');
        }

        $validated = $request->validate([
            'parent_id' => 'required|exists:institutes,id',
            'institute_name' => 'required|string|max:255',
            'branch_name' => 'required|string|max:255',
            'branch_code' => 'nullable|string|max:50|unique:institutes,branch_code',
            'branch_description' => 'nullable|string|max:1000',
            'institute_type' => 'required|in:branch,sub_branch',
            'branch_manager_id' => 'nullable|exists:users,id',
            'contact_person' => 'required|string|max:255',
            'branch_phone' => 'required|string|max:20',
            'branch_email' => 'nullable|email|max:255',
            'branch_address' => 'required|string|max:500',
            'branch_city' => 'required|string|max:100',
            'branch_state' => 'required|string|max:100',
            'branch_pincode' => 'required|string|max:10',
            'branch_area_sqft' => 'nullable|numeric|min:0',
            'max_students_capacity' => 'nullable|integer|min:0',
            'branch_established_date' => 'nullable|date',
            'operating_hours' => 'nullable|array',
            'branch_facilities' => 'nullable|array',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $parentBranch = Institute::find($validated['parent_id']);

        // Verify user can manage parent branch
        if (!$parentBranch->canBeManaged($user)) {
            return redirect()->back()->with('error', 'Unauthorized to create branch here.');
        }

        // Handle gallery images upload
        $galleryImages = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $imagePath = $image->store('branch-gallery', 'public');
                $galleryImages[] = $imagePath;
            }
            $validated['branch_gallery_images'] = $galleryImages;
        }

        // Set additional branch data
        $validated['user_id'] = $institute->user_id; // Same owner as main institute
        $validated['verified'] = false; // New branches need verification

        DB::beginTransaction();
        try {
            // Create the branch
            $branch = $parentBranch->createBranch($validated, $validated['institute_type']);

            // If branch manager is assigned, create teacher profile for them
            if ($validated['branch_manager_id']) {
                $this->assignBranchManager($branch, $validated['branch_manager_id']);
            }

            DB::commit();

            return redirect()->route('institute.branches.show', $branch)
                           ->with('success', 'Branch created successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->with('error', 'Failed to create branch: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Show specific branch details
     */
    public function show(Institute $branch)
    {
        $user = Auth::user();

        if (!$branch->canBeManaged($user)) {
            return redirect()->back()->with('error', 'Unauthorized to view this branch.');
        }

        $branchTeachers = $branch->branchTeachers()->with('user')->paginate(10);
        $childBranches = $branch->childBranches()->activeBranches()->get();
        $stats = $this->getBranchStatistics($branch);

        return view('institute.branches.show', compact('branch', 'branchTeachers', 'childBranches', 'stats'));
    }

    /**
     * Show edit branch form
     */
    public function edit(Institute $branch)
    {
        $user = Auth::user();

        if (!$branch->canBeManaged($user)) {
            return redirect()->back()->with('error', 'Unauthorized to edit this branch.');
        }

        $subjects = Subject::active()->get();
        $branchManagers = User::byRole('teacher')->active()->get();

        return view('institute.branches.edit', compact('branch', 'subjects', 'branchManagers'));
    }

    /**
     * Update branch details
     */
    public function update(Request $request, Institute $branch)
    {
        $user = Auth::user();

        if (!$branch->canBeManaged($user)) {
            return redirect()->back()->with('error', 'Unauthorized to edit this branch.');
        }

        $validated = $request->validate([
            'institute_name' => 'required|string|max:255',
            'branch_name' => 'required|string|max:255',
            'branch_code' => 'nullable|string|max:50|unique:institutes,branch_code,' . $branch->id,
            'branch_description' => 'nullable|string|max:1000',
            'branch_manager_id' => 'nullable|exists:users,id',
            'contact_person' => 'required|string|max:255',
            'branch_phone' => 'required|string|max:20',
            'branch_email' => 'nullable|email|max:255',
            'branch_address' => 'required|string|max:500',
            'branch_city' => 'required|string|max:100',
            'branch_state' => 'required|string|max:100',
            'branch_pincode' => 'required|string|max:10',
            'branch_area_sqft' => 'nullable|numeric|min:0',
            'max_students_capacity' => 'nullable|integer|min:0',
            'branch_established_date' => 'nullable|date',
            'operating_hours' => 'nullable|array',
            'branch_facilities' => 'nullable|array',
            'is_active_branch' => 'boolean',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            $galleryImages = [];
            foreach ($request->file('gallery_images') as $image) {
                $imagePath = $image->store('branch-gallery', 'public');
                $galleryImages[] = $imagePath;
            }
            $validated['branch_gallery_images'] = array_merge(
                $branch->branch_gallery_images ?? [], 
                $galleryImages
            );
        }

        $branch->update($validated);

        // Update branch manager if changed
        if ($validated['branch_manager_id'] !== $branch->branch_manager_id) {
            $this->updateBranchManager($branch, $validated['branch_manager_id']);
        }

        return redirect()->route('institute.branches.show', $branch)
                       ->with('success', 'Branch updated successfully!');
    }

    /**
     * Delete a branch
     */
    public function destroy(Institute $branch)
    {
        $user = Auth::user();

        if (!$branch->canBeManaged($user) || $branch->isMainInstitute()) {
            return redirect()->back()->with('error', 'Cannot delete this branch.');
        }

        // Check if branch has sub-branches
        if ($branch->childBranches()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete branch with sub-branches. Delete sub-branches first.');
        }

        // Check if branch has teachers
        if ($branch->branchTeachers()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete branch with teachers. Transfer teachers first.');
        }

        DB::beginTransaction();
        try {
            $parentBranch = $branch->parentInstitute;
            $branch->delete();
            
            // Update parent branch counts
            if ($parentBranch) {
                $parentBranch->updateBranchCounts();
            }

            DB::commit();

            return redirect()->route('institute.branches.index')
                           ->with('success', 'Branch deleted successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to delete branch: ' . $e->getMessage());
        }
    }

    /**
     * Assign teacher to branch
     */
    public function assignTeacher(Request $request, Institute $branch)
    {
        $user = Auth::user();

        if (!$branch->canBeManaged($user)) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $validated = $request->validate([
            'teacher_id' => 'required|exists:teacher_profiles,id',
            'branch_role' => 'required|in:teacher,coordinator,head_teacher,branch_admin',
            'branch_permissions' => 'nullable|array',
            'branch_notes' => 'nullable|string|max:500',
        ]);

        $teacher = TeacherProfile::find($validated['teacher_id']);

        if ($teacher->assignToBranch($branch, $validated['branch_role'], $validated['branch_permissions'] ?? [])) {
            if (!empty($validated['branch_notes'])) {
                $teacher->update(['branch_notes' => $validated['branch_notes']]);
            }

            return redirect()->back()->with('success', 'Teacher assigned to branch successfully!');
        }

        return redirect()->back()->with('error', 'Failed to assign teacher to branch.');
    }

    /**
     * Remove teacher from branch
     */
    public function removeTeacher(Institute $branch, TeacherProfile $teacher)
    {
        $user = Auth::user();

        if (!$branch->canBeManaged($user)) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        if ($teacher->branch_id === $branch->id) {
            $teacher->removeFromBranch();
            return redirect()->back()->with('success', 'Teacher removed from branch successfully!');
        }

        return redirect()->back()->with('error', 'Teacher not found in this branch.');
    }

    /**
     * Transfer teacher between branches
     */
    public function transferTeacher(Request $request, Institute $fromBranch, TeacherProfile $teacher)
    {
        $user = Auth::user();

        if (!$fromBranch->canBeManaged($user)) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $validated = $request->validate([
            'to_branch_id' => 'required|exists:institutes,id',
            'branch_role' => 'required|in:teacher,coordinator,head_teacher,branch_admin',
            'branch_permissions' => 'nullable|array',
        ]);

        $toBranch = Institute::find($validated['to_branch_id']);

        if (!$toBranch->canBeManaged($user)) {
            return redirect()->back()->with('error', 'Unauthorized to transfer to this branch.');
        }

        if ($teacher->transferToBranch($toBranch, $validated['branch_role'], $validated['branch_permissions'] ?? [])) {
            return redirect()->back()->with('success', 'Teacher transferred successfully!');
        }

        return redirect()->back()->with('error', 'Failed to transfer teacher.');
    }

    /**
     * Verify teacher at branch level
     */
    public function verifyBranchTeacher(Institute $branch, TeacherProfile $teacher)
    {
        $user = Auth::user();

        if (!$branch->canBeManaged($user)) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        if ($branch->verifyBranchTeacher($teacher)) {
            return redirect()->back()->with('success', 'Teacher verified at branch level!');
        }

        return redirect()->back()->with('error', 'Failed to verify teacher.');
    }

    /**
     * Get branch hierarchy for display
     */
    private function getBranchHierarchy(Institute $institute)
    {
        if ($institute->isMainInstitute()) {
            return $institute->allDescendants;
        }
        
        return $institute->getRootInstitute()->allDescendants;
    }

    /**
     * Get comprehensive branch statistics
     */
    private function getBranchStatistics(Institute $institute)
    {
        $stats = [
            'total_branches' => 0,
            'active_branches' => 0,
            'total_teachers' => 0,
            'verified_teachers' => 0,
            'total_capacity' => 0,
            'total_area' => 0,
        ];

        if ($institute->isMainInstitute()) {
            $allBranches = $institute->allDescendants()->activeBranches()->get();
            $stats['total_branches'] = $institute->total_sub_branches;
            $stats['active_branches'] = $allBranches->count();
            $stats['total_teachers'] = $institute->allTeachers()->count();
            $stats['verified_teachers'] = $institute->allTeachers()->branchVerified()->count();
            $stats['total_capacity'] = $allBranches->sum('max_students_capacity');
            $stats['total_area'] = $allBranches->sum('branch_area_sqft');
        } else {
            $stats['total_branches'] = $institute->total_sub_branches;
            $stats['active_branches'] = $institute->childBranches()->activeBranches()->count();
            $stats['total_teachers'] = $institute->branchTeachers()->count();
            $stats['verified_teachers'] = $institute->branchTeachers()->branchVerified()->count();
            $stats['total_capacity'] = $institute->max_students_capacity ?? 0;
            $stats['total_area'] = $institute->branch_area_sqft ?? 0;
        }

        return $stats;
    }

    /**
     * Assign branch manager
     */
    private function assignBranchManager(Institute $branch, $managerId)
    {
        $manager = User::find($managerId);
        
        if ($manager && $manager->isTeacher()) {
            $teacherProfile = $manager->teacherProfile;
            
            if ($teacherProfile) {
                $teacherProfile->assignToBranch($branch, 'branch_admin', ['manage_teachers', 'manage_schedule', 'view_reports']);
                $teacherProfile->update(['is_branch_verified' => true]);
            }
        }
    }

    /**
     * Update branch manager
     */
    private function updateBranchManager(Institute $branch, $newManagerId)
    {
        // Remove old manager's role
        if ($branch->branch_manager_id) {
            $oldManager = User::find($branch->branch_manager_id);
            if ($oldManager && $oldManager->teacherProfile) {
                $oldTeacher = $oldManager->teacherProfile;
                if ($oldTeacher->branch_id === $branch->id && $oldTeacher->branch_role === 'branch_admin') {
                    $oldTeacher->update(['branch_role' => 'teacher', 'branch_permissions' => null]);
                }
            }
        }

        // Assign new manager
        if ($newManagerId) {
            $this->assignBranchManager($branch, $newManagerId);
        }
    }

    /**
     * Get branch tree for JSON API
     */
    public function getBranchTree(Request $request)
    {
        $user = Auth::user();
        $institute = $user->institute;

        if (!$institute) {
            return response()->json(['error' => 'Institute not found'], 404);
        }

        $tree = $this->buildBranchTree($institute);
        return response()->json($tree);
    }

    /**
     * Build hierarchical branch tree
     */
    private function buildBranchTree(Institute $institute)
    {
        $tree = [
            'id' => $institute->id,
            'name' => $institute->display_name,
            'type' => $institute->institute_type,
            'active' => $institute->is_active_branch,
            'teachers_count' => $institute->branchTeachers()->count(),
            'children' => []
        ];

        foreach ($institute->childBranches as $child) {
            $tree['children'][] = $this->buildBranchTree($child);
        }

        return $tree;
    }
}
