<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request and redirect based on user role
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        return match($user->role) {
            'student' => redirect()->route('student.dashboard'),
            'teacher' => redirect()->route('teacher.dashboard'),
            'institute' => redirect()->route('institute.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            default => redirect()->route('student.dashboard'),
        };
    }
}
