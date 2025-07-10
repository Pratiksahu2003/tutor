<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Main public routes and authentication routes
|
*/

// Test routes for debugging
Route::get('/test', function () {
    return view('test-simple');
});

Route::get('/test-layout', function () {
    return view('pages.test-layout');
});

// Homepage and Public Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Additional static pages
Route::get('/terms-and-conditions', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/how-it-works', [PageController::class, 'howItWorks'])->name('how-it-works');
Route::get('/careers', [PageController::class, 'careers'])->name('careers');

// Search and Discovery (Public)
Route::prefix('search')->name('search.')->group(function () {
    Route::get('/teachers', [SearchController::class, 'teachers'])->name('teachers');
    Route::get('/institutes', [SearchController::class, 'institutes'])->name('institutes');
    Route::get('/subjects', [SearchController::class, 'subjects'])->name('subjects');
    Route::get('/advanced', [SearchController::class, 'advanced'])->name('advanced');
    Route::post('/filter', [SearchController::class, 'filter'])->name('filter');
    Route::get('/nearby', [SearchController::class, 'nearby'])->name('nearby');
});

// Teacher and Institute Listings (Public)
Route::prefix('teachers')->name('teachers.')->group(function () {
    Route::get('/', [SearchController::class, 'teacherListing'])->name('index');
    Route::get('/{teacher:slug}', [SearchController::class, 'teacherProfile'])->name('show');
    Route::get('/subject/{subject:slug}', [SearchController::class, 'teachersBySubject'])->name('by-subject');
    Route::get('/city/{city}', [SearchController::class, 'teachersByCity'])->name('by-city');
});

Route::prefix('institutes')->name('institutes.')->group(function () {
    Route::get('/', [SearchController::class, 'instituteListing'])->name('index');
    Route::get('/{institute:slug}', [SearchController::class, 'instituteProfile'])->name('show');
    Route::get('/{institute:slug}/teachers', [SearchController::class, 'instituteTeachers'])->name('teachers');
    Route::get('/city/{city}', [SearchController::class, 'institutesByCity'])->name('by-city');
});

// Blog and Content (Public)
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/category/{category}', [BlogController::class, 'category'])->name('category');
    Route::get('/tag/{tag}', [BlogController::class, 'tag'])->name('tag');
    Route::get('/search', [BlogController::class, 'search'])->name('search');
    Route::get('/{post:slug}', [BlogController::class, 'show'])->name('show');
});

// Lead Generation Forms (Public)
Route::prefix('leads')->name('leads.')->group(function () {
    Route::post('/teacher', [LeadController::class, 'teacherInquiry'])->name('teacher');
    Route::post('/institute', [LeadController::class, 'instituteInquiry'])->name('institute');
    Route::post('/general', [LeadController::class, 'generalInquiry'])->name('general');
    Route::post('/demo', [LeadController::class, 'demoRequest'])->name('demo');
    Route::post('/callback', [LeadController::class, 'callbackRequest'])->name('callback');
    Route::get('/thank-you', [LeadController::class, 'thankYou'])->name('thank-you');
});

// Dynamic CMS Pages (Public)
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

// Authentication Routes (Laravel Breeze)
require __DIR__.'/auth.php';

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Unified Dashboard for All Roles
    Route::get('/dashboard', function () {
        return view('dashboard.unified');
    })->name('dashboard');
});

// Include Modular Route Files
require __DIR__.'/admin.php';
require __DIR__.'/teacher.php';
require __DIR__.'/institute.php';
require __DIR__.'/student.php';

// API Routes for AJAX calls
Route::prefix('api')->name('api.')->group(function () {
    // Public API endpoints
    Route::get('/search/suggestions', [SearchController::class, 'suggestions']);
    Route::get('/cities', [SearchController::class, 'cities']);
    Route::get('/subjects', [SearchController::class, 'subjectsApi']);
    Route::get('/blog/search', [BlogController::class, 'search']);
    
    // Authenticated API endpoints
    Route::middleware('auth')->group(function () {
        Route::post('/favorites/toggle', [SearchController::class, 'toggleFavorite']);
        Route::get('/notifications', [HomeController::class, 'notifications']);
        Route::post('/notifications/mark-read', [HomeController::class, 'markNotificationsRead']);
    });
});
