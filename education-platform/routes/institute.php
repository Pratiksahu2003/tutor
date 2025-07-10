<?php

use App\Http\Controllers\Institute\InstituteDashboardController;
use App\Http\Controllers\Institute\BranchManagementController;
use App\Http\Controllers\Institute\TeacherManagementController;
use App\Http\Controllers\Institute\ProfileController as InstituteProfileController;
use App\Http\Controllers\Institute\AnalyticsController as InstituteAnalyticsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Institute Routes
|--------------------------------------------------------------------------
|
| All institute-related routes with proper middleware protection
|
*/

Route::middleware(['auth', 'role:institute'])->prefix('institute')->name('institute.')->group(function () {
    
    // Dashboard Routes
    Route::get('/dashboard', [InstituteDashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/stats', [InstituteDashboardController::class, 'getStats'])->name('api.stats');
    Route::get('/api/recent-activity', [InstituteDashboardController::class, 'getRecentActivity'])->name('api.activity');
    
    // Profile Management Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [InstituteProfileController::class, 'show'])->name('show');
        Route::get('/edit', [InstituteProfileController::class, 'edit'])->name('edit');
        Route::put('/', [InstituteProfileController::class, 'update'])->name('update');
        Route::post('/logo', [InstituteProfileController::class, 'updateLogo'])->name('logo.update');
        Route::post('/gallery', [InstituteProfileController::class, 'addGalleryImage'])->name('gallery.add');
        Route::delete('/gallery/{image}', [InstituteProfileController::class, 'removeGalleryImage'])->name('gallery.remove');
    });
    
    // Teacher Management Routes
    Route::prefix('teachers')->name('teachers.')->group(function () {
        Route::get('/', [TeacherManagementController::class, 'index'])->name('index');
        Route::get('/create', [TeacherManagementController::class, 'create'])->name('create');
        Route::post('/', [TeacherManagementController::class, 'store'])->name('store');
        Route::get('/{teacher}', [TeacherManagementController::class, 'show'])->name('show');
        Route::get('/{teacher}/edit', [TeacherManagementController::class, 'edit'])->name('edit');
        Route::put('/{teacher}', [TeacherManagementController::class, 'update'])->name('update');
        Route::delete('/{teacher}', [TeacherManagementController::class, 'destroy'])->name('destroy');
        
        // Teacher Actions
        Route::post('/{teacher}/verify', [TeacherManagementController::class, 'verify'])->name('verify');
        Route::post('/{teacher}/unverify', [TeacherManagementController::class, 'unverify'])->name('unverify');
        Route::post('/{teacher}/suspend', [TeacherManagementController::class, 'suspend'])->name('suspend');
        Route::post('/{teacher}/activate', [TeacherManagementController::class, 'activate'])->name('activate');
        
        // Bulk Actions
        Route::post('/bulk/verify', [TeacherManagementController::class, 'bulkVerify'])->name('bulk.verify');
        Route::post('/bulk/suspend', [TeacherManagementController::class, 'bulkSuspend'])->name('bulk.suspend');
        Route::post('/bulk/delete', [TeacherManagementController::class, 'bulkDelete'])->name('bulk.delete');
        
        // Applications & Invitations
        Route::get('/applications/pending', [TeacherManagementController::class, 'pendingApplications'])->name('applications.pending');
        Route::post('/applications/{application}/approve', [TeacherManagementController::class, 'approveApplication'])->name('applications.approve');
        Route::post('/applications/{application}/reject', [TeacherManagementController::class, 'rejectApplication'])->name('applications.reject');
        Route::get('/invite', [TeacherManagementController::class, 'inviteForm'])->name('invite.form');
        Route::post('/invite', [TeacherManagementController::class, 'sendInvitation'])->name('invite.send');
    });
    
    // Branch Management System
    Route::prefix('branches')->name('branches.')->group(function () {
        // Branch CRUD operations
        Route::get('/', [BranchManagementController::class, 'index'])->name('index');
        Route::get('/create', [BranchManagementController::class, 'create'])->name('create');
        Route::post('/', [BranchManagementController::class, 'store'])->name('store');
        Route::get('/{branch}', [BranchManagementController::class, 'show'])->name('show');
        Route::get('/{branch}/edit', [BranchManagementController::class, 'edit'])->name('edit');
        Route::put('/{branch}', [BranchManagementController::class, 'update'])->name('update');
        Route::delete('/{branch}', [BranchManagementController::class, 'destroy'])->name('destroy');
        
        // Branch Teacher Management
        Route::post('/{branch}/teachers/assign', [BranchManagementController::class, 'assignTeacher'])->name('teachers.assign');
        Route::delete('/{branch}/teachers/{teacher}', [BranchManagementController::class, 'removeTeacher'])->name('teachers.remove');
        Route::post('/{branch}/teachers/{teacher}/transfer', [BranchManagementController::class, 'transferTeacher'])->name('teachers.transfer');
        Route::post('/{branch}/teachers/{teacher}/verify', [BranchManagementController::class, 'verifyBranchTeacher'])->name('teachers.verify');
        Route::post('/{branch}/teachers/{teacher}/promote', [BranchManagementController::class, 'promoteTeacher'])->name('teachers.promote');
        Route::post('/{branch}/teachers/{teacher}/demote', [BranchManagementController::class, 'demoteTeacher'])->name('teachers.demote');
        
        // Branch Settings
        Route::get('/{branch}/settings', [BranchManagementController::class, 'settings'])->name('settings');
        Route::put('/{branch}/settings', [BranchManagementController::class, 'updateSettings'])->name('settings.update');
        Route::post('/{branch}/activate', [BranchManagementController::class, 'activate'])->name('activate');
        Route::post('/{branch}/deactivate', [BranchManagementController::class, 'deactivate'])->name('deactivate');
        
        // API endpoints for branch data
        Route::get('/api/tree', [BranchManagementController::class, 'getBranchTree'])->name('api.tree');
        Route::get('/api/{branch}/stats', [BranchManagementController::class, 'getBranchStats'])->name('api.stats');
    });
    
    // Students Management Routes
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [InstituteDashboardController::class, 'students'])->name('index');
        Route::get('/{student}', [InstituteDashboardController::class, 'showStudent'])->name('show');
        Route::get('/inquiries/pending', [InstituteDashboardController::class, 'pendingInquiries'])->name('inquiries.pending');
        Route::post('/inquiries/{inquiry}/respond', [InstituteDashboardController::class, 'respondToInquiry'])->name('inquiries.respond');
    });
    
    // Analytics & Reports Routes
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', [InstituteAnalyticsController::class, 'index'])->name('index');
        Route::get('/teachers', [InstituteAnalyticsController::class, 'teacherAnalytics'])->name('teachers');
        Route::get('/branches', [InstituteAnalyticsController::class, 'branchAnalytics'])->name('branches');
        Route::get('/students', [InstituteAnalyticsController::class, 'studentAnalytics'])->name('students');
        Route::get('/performance', [InstituteAnalyticsController::class, 'performanceMetrics'])->name('performance');
        Route::get('/financial', [InstituteAnalyticsController::class, 'financialReports'])->name('financial');
        Route::get('/export/teachers', [InstituteAnalyticsController::class, 'exportTeachers'])->name('export.teachers');
        Route::get('/export/students', [InstituteAnalyticsController::class, 'exportStudents'])->name('export.students');
    });
    
    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [InstituteDashboardController::class, 'settings'])->name('index');
        Route::get('/general', [InstituteDashboardController::class, 'generalSettings'])->name('general');
        Route::put('/general', [InstituteDashboardController::class, 'updateGeneralSettings'])->name('general.update');
        Route::get('/notifications', [InstituteDashboardController::class, 'notificationSettings'])->name('notifications');
        Route::put('/notifications', [InstituteDashboardController::class, 'updateNotificationSettings'])->name('notifications.update');
        Route::get('/security', [InstituteDashboardController::class, 'securitySettings'])->name('security');
        Route::put('/security', [InstituteDashboardController::class, 'updateSecuritySettings'])->name('security.update');
    });
    
    // Communication Routes
    Route::prefix('communication')->name('communication.')->group(function () {
        Route::get('/messages', [InstituteDashboardController::class, 'messages'])->name('messages');
        Route::post('/messages', [InstituteDashboardController::class, 'sendMessage'])->name('messages.send');
        Route::get('/announcements', [InstituteDashboardController::class, 'announcements'])->name('announcements');
        Route::post('/announcements', [InstituteDashboardController::class, 'createAnnouncement'])->name('announcements.create');
        Route::get('/notifications', [InstituteDashboardController::class, 'notifications'])->name('notifications');
        Route::post('/notifications/mark-read', [InstituteDashboardController::class, 'markNotificationsRead'])->name('notifications.mark-read');
    });
}); 