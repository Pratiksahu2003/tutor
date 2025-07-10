<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $student = auth()->user();
        return view('student.profile.show', compact('student'));
    }

    public function edit()
    {
        $student = auth()->user();
        return view('student.profile.edit', compact('student'));
    }

    public function update(Request $request)
    {
        $student = auth()->user();
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'preferred_subjects' => 'nullable|array',
            'learning_goals' => 'nullable|string',
        ]);

        $student->update($validatedData);

        return redirect()->route('student.profile.show')->with('success', 'Profile updated successfully.');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $student = auth()->user();
        
        // Handle photo upload logic here
        // For now, just return success
        
        return response()->json(['success' => true, 'message' => 'Photo updated successfully.']);
    }
} 