<?php

use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\SearchController as StudentSearchController;
use App\Http\Controllers\Student\InquiryController;
use App\Http\Controllers\Student\BookingController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
|
| All student-related routes with proper middleware protection
|
*/

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    
    // Dashboard Routes
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/stats', [StudentDashboardController::class, 'getStats'])->name('api.stats');
    
    // Profile Management Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [StudentProfileController::class, 'show'])->name('show');
        Route::get('/edit', [StudentProfileController::class, 'edit'])->name('edit');
        Route::put('/', [StudentProfileController::class, 'update'])->name('update');
        Route::post('/photo', [StudentProfileController::class, 'updatePhoto'])->name('photo.update');
    });
    
    // Search & Discovery Routes
    Route::prefix('search')->name('search.')->group(function () {
        Route::get('/teachers', [StudentSearchController::class, 'teachers'])->name('teachers');
        Route::get('/institutes', [StudentSearchController::class, 'institutes'])->name('institutes');
        Route::get('/subjects', [StudentSearchController::class, 'subjects'])->name('subjects');
        Route::get('/advanced', [StudentSearchController::class, 'advanced'])->name('advanced');
        Route::get('/nearby', [StudentSearchController::class, 'nearby'])->name('nearby');
        Route::post('/filters', [StudentSearchController::class, 'applyFilters'])->name('filters');
        Route::get('/saved', [StudentSearchController::class, 'savedSearches'])->name('saved');
        Route::post('/save', [StudentSearchController::class, 'saveSearch'])->name('save');
    });
    
    // Teacher & Institute Inquiry Routes
    Route::prefix('inquiries')->name('inquiries.')->group(function () {
        Route::get('/', [InquiryController::class, 'index'])->name('index');
        Route::get('/create', [InquiryController::class, 'create'])->name('create');
        Route::post('/', [InquiryController::class, 'store'])->name('store');
        Route::get('/{inquiry}', [InquiryController::class, 'show'])->name('show');
        Route::put('/{inquiry}', [InquiryController::class, 'update'])->name('update');
        Route::delete('/{inquiry}', [InquiryController::class, 'destroy'])->name('destroy');
        Route::post('/{inquiry}/follow-up', [InquiryController::class, 'followUp'])->name('follow-up');
        Route::get('/sent/teachers', [InquiryController::class, 'sentToTeachers'])->name('sent.teachers');
        Route::get('/sent/institutes', [InquiryController::class, 'sentToInstitutes'])->name('sent.institutes');
    });
    
    // Booking & Scheduling Routes
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::get('/create/{teacher}', [BookingController::class, 'create'])->name('create');
        Route::post('/', [BookingController::class, 'store'])->name('store');
        Route::get('/{booking}', [BookingController::class, 'show'])->name('show');
        Route::put('/{booking}', [BookingController::class, 'update'])->name('update');
        Route::delete('/{booking}', [BookingController::class, 'cancel'])->name('cancel');
        Route::get('/upcoming', [BookingController::class, 'upcoming'])->name('upcoming');
        Route::get('/past', [BookingController::class, 'past'])->name('past');
        Route::post('/{booking}/reschedule', [BookingController::class, 'reschedule'])->name('reschedule');
        Route::post('/{booking}/feedback', [BookingController::class, 'provideFeedback'])->name('feedback');
    });
    
    // Favorites & Wishlist Routes
    Route::prefix('favorites')->name('favorites.')->group(function () {
        Route::get('/teachers', [StudentDashboardController::class, 'favoriteTeachers'])->name('teachers');
        Route::get('/institutes', [StudentDashboardController::class, 'favoriteInstitutes'])->name('institutes');
        Route::post('/teachers/{teacher}', [StudentDashboardController::class, 'addTeacherToFavorites'])->name('teachers.add');
        Route::delete('/teachers/{teacher}', [StudentDashboardController::class, 'removeTeacherFromFavorites'])->name('teachers.remove');
        Route::post('/institutes/{institute}', [StudentDashboardController::class, 'addInstituteToFavorites'])->name('institutes.add');
        Route::delete('/institutes/{institute}', [StudentDashboardController::class, 'removeInstituteFromFavorites'])->name('institutes.remove');
    });
    
    // Reviews & Ratings Routes
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [StudentDashboardController::class, 'myReviews'])->name('index');
        Route::get('/create/{teacher}', [StudentDashboardController::class, 'createTeacherReview'])->name('teachers.create');
        Route::post('/teachers', [StudentDashboardController::class, 'storeTeacherReview'])->name('teachers.store');
        Route::get('/create/institute/{institute}', [StudentDashboardController::class, 'createInstituteReview'])->name('institutes.create');
        Route::post('/institutes', [StudentDashboardController::class, 'storeInstituteReview'])->name('institutes.store');
        Route::get('/{review}/edit', [StudentDashboardController::class, 'editReview'])->name('edit');
        Route::put('/{review}', [StudentDashboardController::class, 'updateReview'])->name('update');
        Route::delete('/{review}', [StudentDashboardController::class, 'deleteReview'])->name('destroy');
    });
    
    // Communication Routes
    Route::prefix('communication')->name('communication.')->group(function () {
        Route::get('/messages', [StudentDashboardController::class, 'messages'])->name('messages');
        Route::post('/messages', [StudentDashboardController::class, 'sendMessage'])->name('messages.send');
        Route::get('/notifications', [StudentDashboardController::class, 'notifications'])->name('notifications');
        Route::post('/notifications/mark-read', [StudentDashboardController::class, 'markNotificationsRead'])->name('notifications.mark-read');
    });
    
    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [StudentDashboardController::class, 'settings'])->name('index');
        Route::get('/preferences', [StudentDashboardController::class, 'learningPreferences'])->name('preferences');
        Route::put('/preferences', [StudentDashboardController::class, 'updateLearningPreferences'])->name('preferences.update');
        Route::get('/notifications', [StudentDashboardController::class, 'notificationSettings'])->name('notifications');
        Route::put('/notifications', [StudentDashboardController::class, 'updateNotificationSettings'])->name('notifications.update');
        Route::get('/privacy', [StudentDashboardController::class, 'privacySettings'])->name('privacy');
        Route::put('/privacy', [StudentDashboardController::class, 'updatePrivacySettings'])->name('privacy.update');
    });
}); 