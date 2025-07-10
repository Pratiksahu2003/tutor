<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function index()
    {
        $inquiries = collect([]); // Placeholder
        return view('student.inquiries.index', compact('inquiries'));
    }

    public function create()
    {
        return view('student.inquiries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:teacher,institute',
            'target_id' => 'required|integer',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Handle inquiry creation
        return redirect()->route('student.inquiries.index')->with('success', 'Inquiry sent successfully.');
    }

    public function show($inquiryId)
    {
        $inquiry = null; // Placeholder
        return view('student.inquiries.show', compact('inquiry'));
    }

    public function update(Request $request, $inquiryId)
    {
        // Handle inquiry update
        return redirect()->route('student.inquiries.show', $inquiryId)->with('success', 'Inquiry updated successfully.');
    }

    public function destroy($inquiryId)
    {
        // Handle inquiry deletion
        return redirect()->route('student.inquiries.index')->with('success', 'Inquiry deleted successfully.');
    }

    public function followUp(Request $request, $inquiryId)
    {
        // Handle follow-up
        return response()->json(['success' => true, 'message' => 'Follow-up sent successfully.']);
    }

    public function sentToTeachers()
    {
        $inquiries = collect([]); // Placeholder
        return view('student.inquiries.sent-teachers', compact('inquiries'));
    }

    public function sentToInstitutes()
    {
        $inquiries = collect([]); // Placeholder
        return view('student.inquiries.sent-institutes', compact('inquiries'));
    }
} 