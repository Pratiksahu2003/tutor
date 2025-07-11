<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UnifiedDashboardController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\InstituteController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TeacherListingController;
use App\Http\Controllers\InstituteListingController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home Page Route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Teacher and Institute Routes
Route::get('/teachers', [TeacherListingController::class, 'index'])->name('teachers.index');
Route::get('/teachers/create', [TeacherListingController::class, 'create'])->name('teachers.create');
Route::get('/teachers/{teacher}', [TeacherListingController::class, 'show'])->name('teachers.show');
Route::get('/teachers/by-subject/{subject}', [TeacherListingController::class, 'bySubject'])->name('teachers.by-subject');

Route::get('/institutes', [InstituteListingController::class, 'index'])->name('institutes.index');
Route::get('/institutes/{institute}', [InstituteListingController::class, 'show'])->name('institutes.show');

// Search Routes
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/search/teachers', [SearchController::class, 'teachers'])->name('search.teachers');
Route::get('/search/institutes', [SearchController::class, 'institutes'])->name('search.institutes');
Route::get('/search/advanced', [SearchController::class, 'advanced'])->name('search.advanced');
Route::get('/search/nearby', [SearchController::class, 'nearby'])->name('search.nearby');

// Static Pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/how-it-works', [PageController::class, 'howItWorks'])->name('how-it-works');
Route::get('/careers', [PageController::class, 'careers'])->name('careers');

// Blog Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');

// Contact Form
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Authentication Routes
Auth::routes();

// Profile Routes (keep these for the unified dashboard)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/profile', [UnifiedDashboardController::class, 'profile'])->name('dashboard.profile');
    Route::post('/dashboard/profile', [UnifiedDashboardController::class, 'updateProfile'])->name('dashboard.profile.update');
    Route::post('/dashboard/password', [UnifiedDashboardController::class, 'updatePassword'])->name('dashboard.password.update');
    Route::get('/profile', [UnifiedDashboardController::class, 'profile'])->name('profile.edit');
});

// Unified Dashboard Routes
Route::middleware(['auth'])->group(function () {
    // Main unified dashboard - redirect all dashboard requests here
    Route::get('/dashboard', [UnifiedDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/modern', [UnifiedDashboardController::class, 'index'])->name('dashboard.modern');
    
    // Teacher-specific routes
    Route::prefix('teacher')->name('teacher.')->middleware(['role:teacher'])->group(function () {
        Route::post('/subjects', [TeacherController::class, 'storeSubject'])->name('subjects.store');
        Route::post('/sessions', [TeacherController::class, 'storeSession'])->name('sessions.store');
        Route::post('/students', [TeacherController::class, 'storeStudent'])->name('students.store');
        Route::get('/api/stats', [TeacherController::class, 'getStats'])->name('api.stats');
        Route::get('/api/dashboard-updates', [TeacherController::class, 'getUpdates'])->name('api.updates');
    });
    
    // Institute-specific routes
    Route::prefix('institute')->name('institute.')->middleware(['role:institute'])->group(function () {
        Route::post('/branches', [InstituteController::class, 'storeBranch'])->name('branches.store');
        Route::post('/teachers', [InstituteController::class, 'storeTeacher'])->name('teachers.store');
        Route::post('/subjects', [InstituteController::class, 'storeSubject'])->name('subjects.store');
        Route::post('/exam-types', [InstituteController::class, 'storeExamType'])->name('exam-types.store');
        Route::get('/api/stats', [InstituteController::class, 'getStats'])->name('api.stats');
        Route::get('/api/dashboard-updates', [InstituteController::class, 'getUpdates'])->name('api.updates');
    });
});

// Include role-based routes
require __DIR__.'/auth.php';
require __DIR__.'/teacher.php';
require __DIR__.'/student.php';
require __DIR__.'/institute.php';
require __DIR__.'/admin.php';

// Admin Analytics Route
Route::get('/admin/analytics', function() {
    return view('admin.analytics.index');
})->name('admin.analytics.index');
