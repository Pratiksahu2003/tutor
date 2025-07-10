<?php

namespace App\Http\Controllers\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $institute = auth()->user()->institute;
        return view('institute.profile.show', compact('institute'));
    }

    public function edit()
    {
        $institute = auth()->user()->institute;
        return view('institute.profile.edit', compact('institute'));
    }

    public function update(Request $request)
    {
        $institute = auth()->user()->institute;
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'website' => 'nullable|url',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'established_year' => 'nullable|integer|min:1800|max:' . date('Y'),
        ]);

        $institute->update($validatedData);

        return redirect()->route('institute.profile.show')->with('success', 'Profile updated successfully.');
    }

    public function updateLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $institute = auth()->user()->institute;
        
        // Handle logo upload logic here
        // For now, just return success
        
        return response()->json(['success' => true, 'message' => 'Logo updated successfully.']);
    }

    public function verification()
    {
        $institute = auth()->user()->institute;
        return view('institute.profile.verification', compact('institute'));
    }

    public function submitVerification(Request $request)
    {
        $request->validate([
            'verification_documents' => 'required|array',
            'verification_documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        // Handle verification submission logic here
        
        return redirect()->route('institute.profile.verification')->with('success', 'Verification documents submitted successfully.');
    }
} 