<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $teacher = auth()->user()->teacherProfile;
        return view('teacher.schedule.index', compact('teacher'));
    }

    public function calendar()
    {
        $teacher = auth()->user()->teacherProfile;
        // Get calendar data
        $events = collect([]); // Placeholder for calendar events
        
        return view('teacher.schedule.calendar', compact('teacher', 'events'));
    }

    public function setAvailability(Request $request)
    {
        $request->validate([
            'day' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'recurring' => 'boolean'
        ]);

        // Handle availability setting logic here
        
        return response()->json(['success' => true, 'message' => 'Availability set successfully.']);
    }

    public function updateAvailability(Request $request, $slotId)
    {
        $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // Handle availability update logic here
        
        return response()->json(['success' => true, 'message' => 'Availability updated successfully.']);
    }

    public function removeAvailability($slotId)
    {
        // Handle availability removal logic here
        
        return response()->json(['success' => true, 'message' => 'Availability removed successfully.']);
    }

    public function bookingRequests()
    {
        $teacher = auth()->user()->teacherProfile;
        $requests = collect([]); // Placeholder for booking requests
        
        return view('teacher.schedule.booking-requests', compact('teacher', 'requests'));
    }

    public function acceptBooking(Request $request, $requestId)
    {
        // Handle booking acceptance logic here
        
        return response()->json(['success' => true, 'message' => 'Booking request accepted.']);
    }

    public function declineBooking(Request $request, $requestId)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        // Handle booking decline logic here
        
        return response()->json(['success' => true, 'message' => 'Booking request declined.']);
    }
} 