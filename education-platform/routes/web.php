<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

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

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Search Routes
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/teachers', [SearchController::class, 'teachers'])->name('search.teachers');
Route::get('/search/institutes', [SearchController::class, 'institutes'])->name('search.institutes');
Route::post('/search/filter', [SearchController::class, 'filter'])->name('search.filter');

// Teacher and Institute Listing Routes
Route::prefix('teachers')->name('teachers.')->group(function () {
    Route::get('/', [SearchController::class, 'teacherListing'])->name('index');
    Route::get('/{slug}', [SearchController::class, 'teacherProfile'])->name('show');
    Route::get('/subject/{slug}', [SearchController::class, 'teachersBySubject'])->name('by-subject');
    Route::get('/city/{city}', [SearchController::class, 'teachersByCity'])->name('by-city');
});

Route::prefix('institutes')->name('institutes.')->group(function () {
    Route::get('/', [SearchController::class, 'instituteListing'])->name('index');
    Route::get('/{slug}', [SearchController::class, 'instituteProfile'])->name('show');
    Route::get('/{slug}/teachers', [SearchController::class, 'instituteTeachers'])->name('teachers');
    Route::get('/city/{city}', [SearchController::class, 'institutesByCity'])->name('by-city');
});

// Contact Routes
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Lead Routes
Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');

// Page Routes
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/faq/search', [PageController::class, 'faqSearch'])->name('faq.search'); // AJAX route for FAQ search
Route::get('/how-it-works', [PageController::class, 'howItWorks'])->name('how-it-works');
Route::get('/careers', [PageController::class, 'careers'])->name('careers');
Route::get('/page/{slug}', [PageController::class, 'dynamic'])->name('page.dynamic');

// Blog Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Authentication Routes
require __DIR__.'/auth.php';

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Dashboard Routes
    Route::get('/dashboard', function () {
        return redirect()->route('dashboard.modern');
    })->name('dashboard');
    Route::get('/dashboard/modern', [DashboardController::class, 'modern'])->name('dashboard.modern');
    Route::get('/dashboard/profile', [DashboardController::class, 'profile'])->name('dashboard.profile');
    Route::post('/dashboard/profile', [DashboardController::class, 'updateProfile'])->name('dashboard.profile.update');
    Route::post('/dashboard/password', [DashboardController::class, 'updatePassword'])->name('dashboard.password.update');
});

// Include role-based routes
require __DIR__.'/admin.php';
require __DIR__.'/teacher.php';
require __DIR__.'/institute.php';
require __DIR__.'/student.php';
