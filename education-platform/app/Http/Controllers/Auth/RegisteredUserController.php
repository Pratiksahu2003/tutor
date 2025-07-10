<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentProfile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:student,teacher,institute,parent'],
            'phone' => ['nullable', 'string', 'max:20'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'date_of_birth' => ['nullable', 'date'],
            'terms' => ['accepted'],
            
            // Role-specific validation
            'qualification' => ['required_if:role,teacher', 'string', 'max:255'],
            'experience' => ['required_if:role,teacher', 'string'],
            'specialization' => ['required_if:role,teacher', 'string', 'max:255'],
            'institute_name' => ['required_if:role,institute', 'string', 'max:255'],
            'institute_type' => ['required_if:role,institute', 'string'],
            'institute_address' => ['required_if:role,institute', 'string'],
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'city' => $request->city,
            'state' => $request->state,
            'address' => null, // Will be set based on institute address if institute
            'is_active' => true,
            'preferences' => [
                'date_of_birth' => $request->date_of_birth,
                'grade_level' => $request->grade_level,
                'learning_goals' => $request->learning_goals,
                'children_count' => $request->children_count,
                'child_grades' => $request->child_grades,
            ],
        ]);

        // Create role-specific profiles
        $this->createRoleSpecificProfile($user, $request);

        // Assign role
        $this->assignUserRole($user, $request->role);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false))
            ->with('success', 'Welcome to Education Platform! Your account has been created successfully.');
    }

    /**
     * Create role-specific profile
     */
    private function createRoleSpecificProfile($user, $request)
    {
        switch ($request->role) {
            case 'teacher':
                $this->createTeacherProfile($user, $request);
                break;
            case 'institute':
                $this->createInstituteProfile($user, $request);
                break;
            case 'student':
                $this->createStudentProfile($user, $request);
                break;
            case 'parent':
                $this->createParentProfile($user, $request);
                break;
        }
    }

    /**
     * Create teacher profile
     */
    private function createTeacherProfile($user, $request)
    {
        $user->teacherProfile()->create([
            'bio' => 'New teacher on Education Platform',
            'qualification' => $request->qualification,
            'experience' => $this->parseExperience($request->experience),
            'specialization' => $request->specialization,
            'hourly_rate' => 0,
            'teaching_mode' => 'both',
            'is_active' => true,
            'verified' => false,
            'rating' => 0,
            'total_reviews' => 0,
            'slug' => Str::slug($user->name . '-' . $user->id),
        ]);
    }

    /**
     * Create institute profile
     */
    private function createInstituteProfile($user, $request)
    {
        $user->institute()->create([
            'institute_name' => $request->institute_name,
            'institute_type' => $request->institute_type,
            'description' => 'Welcome to ' . $request->institute_name,
            'address' => $request->institute_address,
            'city' => $request->city,
            'state' => $request->state,
            'established_year' => date('Y'),
            'is_active' => true,
            'verified' => false,
            'rating' => 0,
            'total_reviews' => 0,
            'slug' => Str::slug($request->institute_name . '-' . $user->id),
        ]);
    }

    /**
     * Create student profile  
     */
    private function createStudentProfile($user, $request)
    {
        $user->studentProfile()->create([
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'current_class' => $request->grade_level,
            'city' => $request->city,
            'state' => $request->state,
            'learning_goals' => $request->learning_goals ? [$request->learning_goals] : null,
            'preferred_learning_mode' => 'both',
            'urgency' => 'flexible',
            'is_active' => true,
            'profile_completed' => false,
        ]);
    }

    /**
     * Create parent profile
     */
    private function createParentProfile($user, $request)
    {
        // Parents can use the same student features
        // Their children info is stored in preferences
    }

    /**
     * Assign role to user
     */
    private function assignUserRole($user, $roleName)
    {
        $role = \App\Models\Role::where('slug', $roleName)->first();
        if ($role) {
            $user->assignRole($role);
        }
    }

    /**
     * Parse experience string to years
     */
    private function parseExperience($experience)
    {
        switch ($experience) {
            case '0-1': return 1;
            case '1-3': return 2;
            case '3-5': return 4;
            case '5-10': return 7;
            case '10+': return 15;
            default: return 0;
        }
    }
}
