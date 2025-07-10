<?php

use App\Http\Controllers\Teacher\TeacherDashboardController;
use App\Http\Controllers\Teacher\ProfileController as TeacherProfileController;
use App\Http\Controllers\Teacher\ScheduleController;
use App\Http\Controllers\Teacher\StudentsController;
use App\Http\Controllers\Teacher\SubjectsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Teacher Routes
|--------------------------------------------------------------------------
|
| All teacher-related routes with proper middleware protection
|
*/

Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    
    // Dashboard Routes
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/stats', [TeacherDashboardController::class, 'getStats'])->name('api.stats');
    Route::get('/api/recent-activity', [TeacherDashboardController::class, 'getRecentActivity'])->name('api.activity');
    
    // Profile Management Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [TeacherProfileController::class, 'show'])->name('show');
        Route::get('/edit', [TeacherProfileController::class, 'edit'])->name('edit');
        Route::put('/', [TeacherProfileController::class, 'update'])->name('update');
        Route::post('/photo', [TeacherProfileController::class, 'updatePhoto'])->name('photo.update');
        Route::post('/documents', [TeacherProfileController::class, 'uploadDocument'])->name('documents.upload');
        Route::delete('/documents/{document}', [TeacherProfileController::class, 'deleteDocument'])->name('documents.delete');
        Route::get('/verification', [TeacherProfileController::class, 'verification'])->name('verification');
        Route::post('/verification/submit', [TeacherProfileController::class, 'submitVerification'])->name('verification.submit');
    });
    
    // Institute Management Routes
    Route::prefix('institute')->name('institute.')->group(function () {
        Route::get('/search', [TeacherDashboardController::class, 'searchInstitutes'])->name('search');
        Route::post('/apply/{institute}', [TeacherDashboardController::class, 'applyToInstitute'])->name('apply');
        Route::post('/leave', [TeacherDashboardController::class, 'leaveInstitute'])->name('leave');
        Route::get('/applications', [TeacherDashboardController::class, 'viewApplications'])->name('applications');
        Route::delete('/applications/{application}', [TeacherDashboardController::class, 'cancelApplication'])->name('applications.cancel');
    });
    
    // Branch Management Routes
    Route::prefix('branch')->name('branch.')->group(function () {
        Route::get('/current', [TeacherDashboardController::class, 'currentBranch'])->name('current');
        Route::get('/colleagues', [TeacherDashboardController::class, 'branchColleagues'])->name('colleagues');
        Route::get('/schedule', [TeacherDashboardController::class, 'branchSchedule'])->name('schedule');
        Route::post('/request-transfer', [TeacherDashboardController::class, 'requestTransfer'])->name('transfer.request');
    });
    
    // Students Management Routes
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [StudentsController::class, 'index'])->name('index');
        Route::get('/{student}', [StudentsController::class, 'show'])->name('show');
        Route::get('/inquiries/received', [StudentsController::class, 'receivedInquiries'])->name('inquiries.received');
        Route::post('/inquiries/{inquiry}/respond', [StudentsController::class, 'respondToInquiry'])->name('inquiries.respond');
        Route::get('/leads/potential', [StudentsController::class, 'potentialLeads'])->name('leads.potential');
    });
    
    // Subjects Management Routes
    Route::prefix('subjects')->name('subjects.')->group(function () {
        Route::get('/', [SubjectsController::class, 'index'])->name('index');
        Route::post('/add', [SubjectsController::class, 'addSubject'])->name('add');
        Route::put('/{subject}', [SubjectsController::class, 'updateSubjectDetails'])->name('update');
        Route::delete('/{subject}', [SubjectsController::class, 'removeSubject'])->name('remove');
        Route::get('/available', [SubjectsController::class, 'availableSubjects'])->name('available');
    });
    
    // Schedule Management Routes
    Route::prefix('schedule')->name('schedule.')->group(function () {
        Route::get('/', [ScheduleController::class, 'index'])->name('index');
        Route::get('/calendar', [ScheduleController::class, 'calendar'])->name('calendar');
        Route::post('/availability', [ScheduleController::class, 'setAvailability'])->name('availability.set');
        Route::put('/availability/{slot}', [ScheduleController::class, 'updateAvailability'])->name('availability.update');
        Route::delete('/availability/{slot}', [ScheduleController::class, 'removeAvailability'])->name('availability.remove');
        Route::get('/booking-requests', [ScheduleController::class, 'bookingRequests'])->name('booking-requests');
        Route::post('/booking-requests/{request}/accept', [ScheduleController::class, 'acceptBooking'])->name('booking.accept');
        Route::post('/booking-requests/{request}/decline', [ScheduleController::class, 'declineBooking'])->name('booking.decline');
    });
    
    // Analytics & Performance Routes
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', [TeacherDashboardController::class, 'analytics'])->name('index');
        Route::get('/performance', [TeacherDashboardController::class, 'performanceMetrics'])->name('performance');
        Route::get('/earnings', [TeacherDashboardController::class, 'earningsReport'])->name('earnings');
        Route::get('/student-feedback', [TeacherDashboardController::class, 'studentFeedback'])->name('feedback');
        Route::get('/profile-views', [TeacherDashboardController::class, 'profileViews'])->name('profile-views');
    });
    
    // Communication Routes
    Route::prefix('communication')->name('communication.')->group(function () {
        Route::get('/messages', [TeacherDashboardController::class, 'messages'])->name('messages');
        Route::post('/messages', [TeacherDashboardController::class, 'sendMessage'])->name('messages.send');
        Route::get('/notifications', [TeacherDashboardController::class, 'notifications'])->name('notifications');
        Route::post('/notifications/mark-read', [TeacherDashboardController::class, 'markNotificationsRead'])->name('notifications.mark-read');
    });
    
    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [TeacherDashboardController::class, 'settings'])->name('index');
        Route::get('/preferences', [TeacherDashboardController::class, 'preferences'])->name('preferences');
        Route::put('/preferences', [TeacherDashboardController::class, 'updatePreferences'])->name('preferences.update');
        Route::get('/notifications', [TeacherDashboardController::class, 'notificationSettings'])->name('notifications');
        Route::put('/notifications', [TeacherDashboardController::class, 'updateNotificationSettings'])->name('notifications.update');
        Route::get('/privacy', [TeacherDashboardController::class, 'privacySettings'])->name('privacy');
        Route::put('/privacy', [TeacherDashboardController::class, 'updatePrivacySettings'])->name('privacy.update');
    });
}); 