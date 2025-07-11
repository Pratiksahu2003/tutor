<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Institute\InstituteDashboardController;

/*
|--------------------------------------------------------------------------
| Institute Routes
|--------------------------------------------------------------------------
|
| Here is where you can register institute-specific routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "institute" middleware group.
|
*/

Route::middleware(['auth', 'role:institute'])->prefix('institute')->name('institute.')->group(function () {
    // Redirect old dashboard routes to unified dashboard
    Route::get('/dashboard', function () {
        return redirect()->route('dashboard');
    })->name('dashboard');
    
    // API routes for unified dashboard
    Route::get('/api/stats', [InstituteDashboardController::class, 'getStats'])->name('api.stats');
    Route::get('/api/recent-activity', [InstituteDashboardController::class, 'getRecentActivity'])->name('api.activity');
    
    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [InstituteDashboardController::class, 'profile'])->name('index');
        Route::put('/', [InstituteDashboardController::class, 'updateProfile'])->name('update');
        Route::post('/logo', [InstituteDashboardController::class, 'updateLogo'])->name('logo');
        Route::delete('/logo', [InstituteDashboardController::class, 'removeLogo'])->name('logo.remove');
        Route::post('/documents', [InstituteDashboardController::class, 'uploadDocuments'])->name('documents');
        Route::delete('/documents/{document}', [InstituteDashboardController::class, 'deleteDocument'])->name('documents.delete');
    });
    
    // Branch Management
    Route::prefix('branches')->name('branches.')->group(function () {
        Route::get('/', [InstituteDashboardController::class, 'branches'])->name('index');
        Route::get('/create', [InstituteDashboardController::class, 'createBranch'])->name('create');
        Route::post('/', [InstituteDashboardController::class, 'storeBranch'])->name('store');
        Route::get('/{branch}', [InstituteDashboardController::class, 'showBranch'])->name('show');
        Route::get('/{branch}/edit', [InstituteDashboardController::class, 'editBranch'])->name('edit');
        Route::put('/{branch}', [InstituteDashboardController::class, 'updateBranch'])->name('update');
        Route::delete('/{branch}', [InstituteDashboardController::class, 'deleteBranch'])->name('delete');
        Route::get('/{branch}/teachers', [InstituteDashboardController::class, 'branchTeachers'])->name('teachers');
        Route::get('/{branch}/students', [InstituteDashboardController::class, 'branchStudents'])->name('students');
        Route::get('/{branch}/sessions', [InstituteDashboardController::class, 'branchSessions'])->name('sessions');
    });
    
    // Teacher Management
    Route::prefix('teachers')->name('teachers.')->group(function () {
        Route::get('/', [InstituteDashboardController::class, 'teachers'])->name('index');
        Route::get('/create', [InstituteDashboardController::class, 'createTeacher'])->name('create');
        Route::post('/', [InstituteDashboardController::class, 'storeTeacher'])->name('store');
        Route::get('/{teacher}', [InstituteDashboardController::class, 'showTeacher'])->name('show');
        Route::get('/{teacher}/edit', [InstituteDashboardController::class, 'editTeacher'])->name('edit');
        Route::put('/{teacher}', [InstituteDashboardController::class, 'updateTeacher'])->name('update');
        Route::delete('/{teacher}', [InstituteDashboardController::class, 'deleteTeacher'])->name('delete');
        Route::get('/{teacher}/performance', [InstituteDashboardController::class, 'teacherPerformance'])->name('performance');
        Route::get('/{teacher}/sessions', [InstituteDashboardController::class, 'teacherSessions'])->name('sessions');
    });
    
    // Subject Management
    Route::prefix('subjects')->name('subjects.')->group(function () {
        Route::get('/', [InstituteDashboardController::class, 'subjects'])->name('index');
        Route::get('/create', [InstituteDashboardController::class, 'createSubject'])->name('create');
        Route::post('/', [InstituteDashboardController::class, 'storeSubject'])->name('store');
        Route::get('/{subject}', [InstituteDashboardController::class, 'showSubject'])->name('show');
        Route::get('/{subject}/edit', [InstituteDashboardController::class, 'editSubject'])->name('edit');
        Route::put('/{subject}', [InstituteDashboardController::class, 'updateSubject'])->name('update');
        Route::delete('/{subject}', [InstituteDashboardController::class, 'deleteSubject'])->name('delete');
    });
    
    // Exam Type Management
    Route::prefix('exam-types')->name('exam-types.')->group(function () {
        Route::get('/', [InstituteDashboardController::class, 'examTypes'])->name('index');
        Route::get('/create', [InstituteDashboardController::class, 'createExamType'])->name('create');
        Route::post('/', [InstituteDashboardController::class, 'storeExamType'])->name('store');
        Route::get('/{examType}', [InstituteDashboardController::class, 'showExamType'])->name('show');
        Route::get('/{examType}/edit', [InstituteDashboardController::class, 'editExamType'])->name('edit');
        Route::put('/{examType}', [InstituteDashboardController::class, 'updateExamType'])->name('update');
        Route::delete('/{examType}', [InstituteDashboardController::class, 'deleteExamType'])->name('delete');
    });
    
    // Student Management
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [InstituteDashboardController::class, 'students'])->name('index');
        Route::get('/{student}', [InstituteDashboardController::class, 'showStudent'])->name('show');
        Route::get('/{student}/progress', [InstituteDashboardController::class, 'studentProgress'])->name('progress');
        Route::get('/{student}/sessions', [InstituteDashboardController::class, 'studentSessions'])->name('sessions');
    });
    
    // Analytics & Reports
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', [InstituteDashboardController::class, 'analytics'])->name('index');
        Route::get('/revenue', [InstituteDashboardController::class, 'revenueReport'])->name('revenue');
        Route::get('/branch-performance', [InstituteDashboardController::class, 'branchPerformance'])->name('branch-performance');
        Route::get('/teacher-performance', [InstituteDashboardController::class, 'teacherPerformance'])->name('teacher-performance');
        Route::get('/student-enrollment', [InstituteDashboardController::class, 'studentEnrollment'])->name('student-enrollment');
    });
    
    // Communication
    Route::prefix('communication')->name('communication.')->group(function () {
        Route::get('/messages', [InstituteDashboardController::class, 'messages'])->name('messages');
        Route::post('/messages', [InstituteDashboardController::class, 'sendMessage'])->name('messages.send');
        Route::get('/notifications', [InstituteDashboardController::class, 'notifications'])->name('notifications');
        Route::post('/notifications/mark-read', [InstituteDashboardController::class, 'markNotificationsRead'])->name('notifications.mark-read');
    });
    
    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [InstituteDashboardController::class, 'settings'])->name('index');
        Route::get('/preferences', [InstituteDashboardController::class, 'preferences'])->name('preferences');
        Route::put('/preferences', [InstituteDashboardController::class, 'updatePreferences'])->name('preferences.update');
        Route::get('/notifications', [InstituteDashboardController::class, 'notificationSettings'])->name('notifications');
        Route::put('/notifications', [InstituteDashboardController::class, 'updateNotificationSettings'])->name('notifications.update');
        Route::get('/privacy', [InstituteDashboardController::class, 'privacySettings'])->name('privacy');
        Route::put('/privacy', [InstituteDashboardController::class, 'updatePrivacySettings'])->name('privacy.update');
    });
}); 