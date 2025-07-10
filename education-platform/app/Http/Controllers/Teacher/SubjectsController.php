<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectsController extends Controller
{
    public function index()
    {
        $teacher = auth()->user()->teacherProfile;
        $subjects = $teacher->subjects ?? collect([]);
        $availableSubjects = Subject::all();
        
        return view('teacher.subjects.index', compact('teacher', 'subjects', 'availableSubjects'));
    }

    public function addSubject(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'proficiency_level' => 'required|string|in:beginner,intermediate,advanced,expert',
            'experience_years' => 'nullable|integer|min:0',
            'teaching_levels' => 'required|array',
            'teaching_levels.*' => 'string|in:primary,secondary,higher_secondary,university'
        ]);

        $teacher = auth()->user()->teacherProfile;
        
        // Check if subject is already added
        if ($teacher->subjects && $teacher->subjects->contains('id', $request->subject_id)) {
            return response()->json(['success' => false, 'message' => 'Subject already added.']);
        }

        // Handle adding subject logic here
        
        return response()->json(['success' => true, 'message' => 'Subject added successfully.']);
    }

    public function updateSubjectDetails(Request $request, $subjectId)
    {
        $request->validate([
            'proficiency_level' => 'required|string|in:beginner,intermediate,advanced,expert',
            'experience_years' => 'nullable|integer|min:0',
            'teaching_levels' => 'required|array',
            'teaching_levels.*' => 'string|in:primary,secondary,higher_secondary,university'
        ]);

        // Handle updating subject details logic here
        
        return response()->json(['success' => true, 'message' => 'Subject details updated successfully.']);
    }

    public function removeSubject($subjectId)
    {
        // Handle removing subject logic here
        
        return response()->json(['success' => true, 'message' => 'Subject removed successfully.']);
    }

    public function availableSubjects()
    {
        $teacher = auth()->user()->teacherProfile;
        $teacherSubjectIds = $teacher->subjects ? $teacher->subjects->pluck('id')->toArray() : [];
        
        $availableSubjects = Subject::whereNotIn('id', $teacherSubjectIds)->get();
        
        return response()->json(['subjects' => $availableSubjects]);
    }
} 