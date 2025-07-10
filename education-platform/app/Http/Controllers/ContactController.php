<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display the contact page
     */
    public function index()
    {
        return view('pages.contact');
    }

    /**
     * Store a contact form submission
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Here you would typically store the contact submission
        // For now, just return success response
        
        return back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }
} 