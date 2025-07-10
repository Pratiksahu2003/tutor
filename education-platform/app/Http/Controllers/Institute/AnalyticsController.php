<?php

namespace App\Http\Controllers\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        $institute = auth()->user()->institute;
        return view('institute.analytics.index', compact('institute'));
    }

    public function teacherAnalytics()
    {
        $institute = auth()->user()->institute;
        return view('institute.analytics.teachers', compact('institute'));
    }

    public function branchAnalytics()
    {
        $institute = auth()->user()->institute;
        return view('institute.analytics.branches', compact('institute'));
    }

    public function studentAnalytics()
    {
        $institute = auth()->user()->institute;
        return view('institute.analytics.students', compact('institute'));
    }

    public function performanceMetrics()
    {
        $institute = auth()->user()->institute;
        return view('institute.analytics.performance', compact('institute'));
    }

    public function financialReports()
    {
        $institute = auth()->user()->institute;
        return view('institute.analytics.financial', compact('institute'));
    }

    public function exportTeachers()
    {
        // Handle teachers export
        return response()->download('path/to/file.csv');
    }

    public function exportStudents()
    {
        // Handle students export
        return response()->download('path/to/file.csv');
    }
} 