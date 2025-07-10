<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $teacher = auth()->user()->teacherProfile;
        return view('teacher.profile.show', compact('teacher'));
    }

    public function edit()
    {
        $teacher = auth()->user()->teacherProfile;
        return view('teacher.profile.edit', compact('teacher'));
    }

    public function update(Request $request)
    {
        $teacher = auth()->user()->teacherProfile;
        
        $validatedData = $request->validate([
            'specialization' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'bio' => 'nullable|string',
            'qualifications' => 'nullable|string',
            'teaching_mode' => 'nullable|string|in:online,offline,both',
        ]);

        $teacher->update($validatedData);

        return redirect()->route('teacher.profile.show')->with('success', 'Profile updated successfully.');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $teacher = auth()->user()->teacherProfile;
        
        // Handle photo upload logic here
        // For now, just return success
        
        return response()->json(['success' => true, 'message' => 'Photo updated successfully.']);
    }

    public function uploadDocument(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'document_type' => 'required|string'
        ]);

        // Handle document upload logic here
        
        return response()->json(['success' => true, 'message' => 'Document uploaded successfully.']);
    }

    public function deleteDocument($documentId)
    {
        // Handle document deletion logic here
        
        return response()->json(['success' => true, 'message' => 'Document deleted successfully.']);
    }

    public function verification()
    {
        $teacher = auth()->user()->teacherProfile;
        return view('teacher.profile.verification', compact('teacher'));
    }

    public function submitVerification(Request $request)
    {
        $request->validate([
            'verification_documents' => 'required|array',
            'verification_documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        // Handle verification submission logic here
        
        return redirect()->route('teacher.profile.verification')->with('success', 'Verification documents submitted successfully.');
    }
} 