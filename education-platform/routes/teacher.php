<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Teacher\TeacherDashboardController;

/*
|--------------------------------------------------------------------------
| Teacher Routes
|--------------------------------------------------------------------------
|
| Here is where you can register teacher-specific routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "teacher" middleware group.
|
*/

Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    // Redirect old dashboard routes to unified dashboard
    Route::get('/dashboard', function () {
        return redirect()->route('dashboard');
    })->name('dashboard');
    
    // API routes for unified dashboard
    Route::get('/api/stats', [TeacherDashboardController::class, 'getStats'])->name('api.stats');
    Route::get('/api/recent-activity', [TeacherDashboardController::class, 'getRecentActivity'])->name('api.activity');
    
    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [TeacherDashboardController::class, 'profile'])->name('index');
        Route::put('/', [TeacherDashboardController::class, 'updateProfile'])->name('update');
        Route::post('/avatar', [TeacherDashboardController::class, 'updateAvatar'])->name('avatar');
        Route::delete('/avatar', [TeacherDashboardController::class, 'removeAvatar'])->name('avatar.remove');
        Route::post('/documents', [TeacherDashboardController::class, 'uploadDocuments'])->name('documents');
        Route::delete('/documents/{document}', [TeacherDashboardController::class, 'deleteDocument'])->name('documents.delete');
    });
    
    // Institute Management
    Route::prefix('institutes')->name('institutes.')->group(function () {
        Route::get('/', [TeacherDashboardController::class, 'institutes'])->name('index');
        Route::get('/search', [TeacherDashboardController::class, 'searchInstitutes'])->name('search');
        Route::post('/apply/{institute}', [TeacherDashboardController::class, 'applyToInstitute'])->name('apply');
        Route::post('/leave', [TeacherDashboardController::class, 'leaveInstitute'])->name('leave');
        Route::get('/applications', [TeacherDashboardController::class, 'viewApplications'])->name('applications');
        Route::delete('/applications/{application}', [TeacherDashboardController::class, 'cancelApplication'])->name('applications.cancel');
    });
    
    // Branch Management
    Route::prefix('branches')->name('branches.')->group(function () {
        Route::get('/', [TeacherDashboardController::class, 'branches'])->name('index');
        Route::get('/current', [TeacherDashboardController::class, 'currentBranch'])->name('current');
        Route::get('/colleagues', [TeacherDashboardController::class, 'branchColleagues'])->name('colleagues');
        Route::get('/schedule', [TeacherDashboardController::class, 'branchSchedule'])->name('schedule');
        Route::post('/request-transfer', [TeacherDashboardController::class, 'requestTransfer'])->name('transfer.request');
    });
    
    // Session Management
    Route::prefix('sessions')->name('sessions.')->group(function () {
        Route::get('/', [TeacherDashboardController::class, 'sessions'])->name('index');
        Route::get('/upcoming', [TeacherDashboardController::class, 'upcomingSessions'])->name('upcoming');
        Route::get('/completed', [TeacherDashboardController::class, 'completedSessions'])->name('completed');
        Route::get('/cancelled', [TeacherDashboardController::class, 'cancelledSessions'])->name('cancelled');
        Route::get('/create', [TeacherDashboardController::class, 'createSession'])->name('create');
        Route::post('/', [TeacherDashboardController::class, 'storeSession'])->name('store');
        Route::get('/{session}', [TeacherDashboardController::class, 'showSession'])->name('show');
        Route::get('/{session}/edit', [TeacherDashboardController::class, 'editSession'])->name('edit');
        Route::put('/{session}', [TeacherDashboardController::class, 'updateSession'])->name('update');
        Route::delete('/{session}', [TeacherDashboardController::class, 'cancelSession'])->name('cancel');
        Route::post('/{session}/complete', [TeacherDashboardController::class, 'completeSession'])->name('complete');
    });
    
    // Student Management
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [TeacherDashboardController::class, 'students'])->name('index');
        Route::get('/{student}', [TeacherDashboardController::class, 'showStudent'])->name('show');
        Route::get('/{student}/progress', [TeacherDashboardController::class, 'studentProgress'])->name('progress');
        Route::post('/{student}/notes', [TeacherDashboardController::class, 'addStudentNote'])->name('notes');
        Route::delete('/{student}/notes/{note}', [TeacherDashboardController::class, 'deleteStudentNote'])->name('notes.delete');
    });
    
    // Analytics & Reports - Redirect to admin dashboard for comprehensive reports
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', function() {
            return redirect()->route('admin.analytics.index')->with('info', 'Please use the admin dashboard for comprehensive reports.');
        })->name('index');
        Route::get('/performance', function() {
            return redirect()->route('admin.analytics.index')->with('info', 'Please use the admin dashboard for comprehensive reports.');
        })->name('performance');
        Route::get('/earnings', function() {
            return redirect()->route('admin.analytics.index')->with('info', 'Please use the admin dashboard for comprehensive reports.');
        })->name('earnings');
        Route::get('/student-feedback', function() {
            return redirect()->route('admin.analytics.index')->with('info', 'Please use the admin dashboard for comprehensive reports.');
        })->name('feedback');
        Route::get('/profile-views', function() {
            return redirect()->route('admin.analytics.index')->with('info', 'Please use the admin dashboard for comprehensive reports.');
        })->name('profile-views');
    });
    
    // Communication
    Route::prefix('communication')->name('communication.')->group(function () {
        Route::get('/messages', [TeacherDashboardController::class, 'messages'])->name('messages');
        Route::post('/messages', [TeacherDashboardController::class, 'sendMessage'])->name('messages.send');
        Route::get('/notifications', [TeacherDashboardController::class, 'notifications'])->name('notifications');
        Route::post('/notifications/mark-read', [TeacherDashboardController::class, 'markNotificationsRead'])->name('notifications.mark-read');
    });
    
    // Settings
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