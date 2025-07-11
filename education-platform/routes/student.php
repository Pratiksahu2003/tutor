<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\StudentDashboardController;

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
|
| Here is where you can register student-specific routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "student" middleware group.
|
*/

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    // Redirect old dashboard routes to unified dashboard
    Route::get('/dashboard', function () {
        return redirect()->route('dashboard');
    })->name('dashboard');
    
    // API routes for unified dashboard
    Route::get('/api/stats', [StudentDashboardController::class, 'getStats'])->name('api.stats');
    Route::get('/api/recent-activity', [StudentDashboardController::class, 'getRecentActivity'])->name('api.activity');
    
    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [StudentDashboardController::class, 'profile'])->name('index');
        Route::put('/', [StudentDashboardController::class, 'updateProfile'])->name('update');
        Route::post('/avatar', [StudentDashboardController::class, 'updateAvatar'])->name('avatar');
        Route::delete('/avatar', [StudentDashboardController::class, 'removeAvatar'])->name('avatar.remove');
    });
    
    // Learning Management
    Route::prefix('learning')->name('learning.')->group(function () {
        Route::get('/', [StudentDashboardController::class, 'learning'])->name('index');
        Route::get('/subjects', [StudentDashboardController::class, 'subjects'])->name('subjects');
        Route::get('/progress', [StudentDashboardController::class, 'progress'])->name('progress');
        Route::get('/goals', [StudentDashboardController::class, 'goals'])->name('goals');
        Route::post('/goals', [StudentDashboardController::class, 'updateGoals'])->name('goals.update');
    });
    
    // Session Management
    Route::prefix('sessions')->name('sessions.')->group(function () {
        Route::get('/', [StudentDashboardController::class, 'sessions'])->name('index');
        Route::get('/upcoming', [StudentDashboardController::class, 'upcomingSessions'])->name('upcoming');
        Route::get('/completed', [StudentDashboardController::class, 'completedSessions'])->name('completed');
        Route::get('/{session}', [StudentDashboardController::class, 'showSession'])->name('show');
        Route::post('/{session}/book', [StudentDashboardController::class, 'bookSession'])->name('book');
        Route::post('/{session}/cancel', [StudentDashboardController::class, 'cancelSession'])->name('cancel');
    });
    
    // Teacher Management
    Route::prefix('teachers')->name('teachers.')->group(function () {
        Route::get('/', [StudentDashboardController::class, 'teachers'])->name('index');
        Route::get('/search', [StudentDashboardController::class, 'searchTeachers'])->name('search');
        Route::get('/{teacher}', [StudentDashboardController::class, 'showTeacher'])->name('show');
        Route::post('/{teacher}/favorite', [StudentDashboardController::class, 'addTeacherToFavorites'])->name('favorite');
        Route::delete('/{teacher}/favorite', [StudentDashboardController::class, 'removeTeacherFromFavorites'])->name('unfavorite');
        Route::post('/{teacher}/inquiry', [StudentDashboardController::class, 'sendInquiry'])->name('inquiry');
    });
    
    // Institute Management
    Route::prefix('institutes')->name('institutes.')->group(function () {
        Route::get('/', [StudentDashboardController::class, 'institutes'])->name('index');
        Route::get('/search', [StudentDashboardController::class, 'searchInstitutes'])->name('search');
        Route::get('/{institute}', [StudentDashboardController::class, 'showInstitute'])->name('show');
        Route::post('/{institute}/favorite', [StudentDashboardController::class, 'addInstituteToFavorites'])->name('favorite');
        Route::delete('/{institute}/favorite', [StudentDashboardController::class, 'removeInstituteFromFavorites'])->name('unfavorite');
        Route::post('/{institute}/enroll', [StudentDashboardController::class, 'enrollInInstitute'])->name('enroll');
    });
    
    // Reviews & Ratings
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
    
    // Communication
    Route::prefix('communication')->name('communication.')->group(function () {
        Route::get('/messages', [StudentDashboardController::class, 'messages'])->name('messages');
        Route::post('/messages', [StudentDashboardController::class, 'sendMessage'])->name('messages.send');
        Route::get('/notifications', [StudentDashboardController::class, 'notifications'])->name('notifications');
        Route::post('/notifications/mark-read', [StudentDashboardController::class, 'markNotificationsRead'])->name('notifications.mark-read');
    });
    
    // Settings
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