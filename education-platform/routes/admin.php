<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\TeacherManagementController;
use App\Http\Controllers\Admin\InstituteManagementController;
use App\Http\Controllers\Admin\CMSController;
use App\Http\Controllers\Admin\QuestionManagementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| All admin-related routes with proper permissions and middleware
|
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Main Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/', [AdminDashboardController::class, 'index'])->name('home');
    
    // Dashboard API routes
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/stats', [AdminDashboardController::class, 'getStats']);
        Route::get('/activity', [AdminDashboardController::class, 'getRecentActivity']);
    });
    
    // ===== USER MANAGEMENT =====
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/create', [UserManagementController::class, 'create'])->name('create');
        Route::post('/', [UserManagementController::class, 'store'])->name('store');
        Route::get('/{user}', [UserManagementController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{user}/assign-role', [UserManagementController::class, 'assignRole'])->name('assign-role');
        Route::delete('/{user}/remove-role', [UserManagementController::class, 'removeRole'])->name('remove-role');
        Route::get('/by-role/ajax', [UserManagementController::class, 'getUsersByRole'])->name('by-role-ajax');
    });

    // ===== ROLE & PERMISSION MANAGEMENT =====
    // Roles
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RolePermissionController::class, 'rolesIndex'])->name('index');
        Route::get('/create', [RolePermissionController::class, 'rolesCreate'])->name('create');
        Route::post('/', [RolePermissionController::class, 'rolesStore'])->name('store');
        Route::get('/{role}/edit', [RolePermissionController::class, 'rolesEdit'])->name('edit');
        Route::put('/{role}', [RolePermissionController::class, 'rolesUpdate'])->name('update');
        Route::delete('/{role}', [RolePermissionController::class, 'rolesDestroy'])->name('destroy');
    });

    // Permissions
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [RolePermissionController::class, 'permissionsIndex'])->name('index');
        Route::get('/create', [RolePermissionController::class, 'permissionsCreate'])->name('create');
        Route::post('/', [RolePermissionController::class, 'permissionsStore'])->name('store');
        Route::get('/{permission}/edit', [RolePermissionController::class, 'permissionsEdit'])->name('edit');
        Route::put('/{permission}', [RolePermissionController::class, 'permissionsUpdate'])->name('update');
        Route::delete('/{permission}', [RolePermissionController::class, 'permissionsDestroy'])->name('destroy');
        Route::post('/initialize', [RolePermissionController::class, 'initializePermissions'])->name('initialize');
    });

    // Role-Permission Actions (AJAX)
    Route::post('/role-permission/assign', [RolePermissionController::class, 'assignPermissionToRole'])->name('role-permission.assign');
    Route::delete('/role-permission/remove', [RolePermissionController::class, 'removePermissionFromRole'])->name('role-permission.remove');

    // ===== TEACHER MANAGEMENT =====
    Route::prefix('teachers')->name('teachers.')->group(function () {
        Route::get('/', [TeacherManagementController::class, 'index'])->name('index');
        Route::get('/create', [TeacherManagementController::class, 'create'])->name('create');
        Route::post('/', [TeacherManagementController::class, 'store'])->name('store');
        Route::get('/{teacher}', [TeacherManagementController::class, 'show'])->name('show');
        Route::get('/{teacher}/edit', [TeacherManagementController::class, 'edit'])->name('edit');
        Route::put('/{teacher}', [TeacherManagementController::class, 'update'])->name('update');
        Route::delete('/{teacher}', [TeacherManagementController::class, 'destroy'])->name('destroy');
        Route::patch('/{teacher}/verify', [TeacherManagementController::class, 'verify'])->name('verify');
        Route::patch('/{teacher}/unverify', [TeacherManagementController::class, 'unverify'])->name('unverify');
        Route::patch('/{teacher}/toggle-status', [TeacherManagementController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/statistics/data', [TeacherManagementController::class, 'statistics'])->name('statistics');
        Route::post('/bulk/verify', [TeacherManagementController::class, 'bulkVerify'])->name('bulk-verify');
        Route::post('/bulk/toggle-status', [TeacherManagementController::class, 'bulkToggleStatus'])->name('bulk-toggle-status');
    });

    // ===== INSTITUTE MANAGEMENT =====
    Route::prefix('institutes')->name('institutes.')->group(function () {
        Route::get('/', [InstituteManagementController::class, 'index'])->name('index');
        Route::get('/create', [InstituteManagementController::class, 'create'])->name('create');
        Route::post('/', [InstituteManagementController::class, 'store'])->name('store');
        Route::get('/{institute}', [InstituteManagementController::class, 'show'])->name('show');
        Route::get('/{institute}/edit', [InstituteManagementController::class, 'edit'])->name('edit');
        Route::put('/{institute}', [InstituteManagementController::class, 'update'])->name('update');
        Route::delete('/{institute}', [InstituteManagementController::class, 'destroy'])->name('destroy');
        Route::patch('/{institute}/verify', [InstituteManagementController::class, 'verify'])->name('verify');
        Route::patch('/{institute}/unverify', [InstituteManagementController::class, 'unverify'])->name('unverify');
        Route::patch('/{institute}/toggle-status', [InstituteManagementController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/statistics/data', [InstituteManagementController::class, 'statistics'])->name('statistics');
        Route::post('/bulk/verify', [InstituteManagementController::class, 'bulkVerify'])->name('bulk-verify');
        Route::post('/bulk/toggle-status', [InstituteManagementController::class, 'bulkToggleStatus'])->name('bulk-toggle-status');
        Route::patch('/{institute}/rating', [InstituteManagementController::class, 'updateRating'])->name('update-rating');
        Route::patch('/{institute}/student-count', [InstituteManagementController::class, 'updateStudentCount'])->name('update-student-count');
    });

    // ===== QUESTION MANAGEMENT =====
    Route::prefix('questions')->name('questions.')->group(function () {
        Route::get('/', [QuestionManagementController::class, 'index'])->name('index');
        Route::get('/create', [QuestionManagementController::class, 'create'])->name('create');
        Route::post('/', [QuestionManagementController::class, 'store'])->name('store');
        Route::get('/{question}', [QuestionManagementController::class, 'show'])->name('show');
        Route::get('/{question}/edit', [QuestionManagementController::class, 'edit'])->name('edit');
        Route::put('/{question}', [QuestionManagementController::class, 'update'])->name('update');
        Route::delete('/{question}', [QuestionManagementController::class, 'destroy'])->name('destroy');
        
        // Question Actions
        Route::patch('/{question}/publish', [QuestionManagementController::class, 'publish'])->name('publish');
        Route::patch('/{question}/archive', [QuestionManagementController::class, 'archive'])->name('archive');
        Route::patch('/{question}/mark-review', [QuestionManagementController::class, 'markForReview'])->name('mark-review');
        Route::patch('/{question}/complete-review', [QuestionManagementController::class, 'completeReview'])->name('complete-review');
        
        // Bulk Operations
        Route::post('/bulk-action', [QuestionManagementController::class, 'bulkAction'])->name('bulk-action');
        
        // Import/Export
        Route::get('/import/form', [QuestionManagementController::class, 'import'])->name('import');
        Route::get('/export', [QuestionManagementController::class, 'export'])->name('export');
        
        // Statistics
        Route::get('/statistics/overview', [QuestionManagementController::class, 'statistics'])->name('statistics');
    });

    // ===== CMS MANAGEMENT =====
    Route::prefix('cms')->name('cms.')->group(function () {
        // Pages Management
        Route::prefix('pages')->name('pages.')->group(function () {
            Route::get('/', [CMSController::class, 'pages'])->name('index');
            Route::get('/create', [CMSController::class, 'createPage'])->name('create');
            Route::post('/', [CMSController::class, 'storePage'])->name('store');
            Route::get('/{page}', [CMSController::class, 'showPage'])->name('show');
            Route::get('/{page}/edit', [CMSController::class, 'editPage'])->name('edit');
            Route::put('/{page}', [CMSController::class, 'updatePage'])->name('update');
            Route::delete('/{page}', [CMSController::class, 'destroyPage'])->name('destroy');
            Route::post('/{page}/publish', [CMSController::class, 'publishPage'])->name('publish');
        });
        
        // Blog Management
        Route::prefix('blog')->name('blog.')->group(function () {
            Route::get('/', [CMSController::class, 'blogPosts'])->name('index');
            Route::get('/create', [CMSController::class, 'createBlogPost'])->name('create');
            Route::post('/', [CMSController::class, 'storeBlogPost'])->name('store');
            Route::get('/{post}', [CMSController::class, 'showBlogPost'])->name('show');
            Route::get('/{post}/edit', [CMSController::class, 'editBlogPost'])->name('edit');
            Route::put('/{post}', [CMSController::class, 'updateBlogPost'])->name('update');
            Route::delete('/{post}', [CMSController::class, 'destroyBlogPost'])->name('destroy');
            Route::post('/{post}/publish', [CMSController::class, 'publishBlogPost'])->name('publish');
        });
        
        // Media Management
        Route::prefix('media')->name('media.')->group(function () {
            Route::get('/', [CMSController::class, 'mediaLibrary'])->name('index');
            Route::post('/upload', [CMSController::class, 'uploadMedia'])->name('upload');
            Route::delete('/{media}', [CMSController::class, 'deleteMedia'])->name('delete');
            Route::get('/folders', [CMSController::class, 'mediaFolders'])->name('folders');
            Route::post('/folders', [CMSController::class, 'createFolder'])->name('folders.create');
        });
    });

    // ===== SETTINGS MANAGEMENT =====
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('index');
        Route::put('/', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('update');
        Route::post('/clear-cache', [App\Http\Controllers\Admin\SettingsController::class, 'clearCache'])->name('clear-cache');
        Route::get('/system-info', [App\Http\Controllers\Admin\SettingsController::class, 'systemInfo'])->name('system-info');
        Route::post('/optimize', [App\Http\Controllers\Admin\SettingsController::class, 'optimizeApplication'])->name('optimize');
        Route::get('/backup', [App\Http\Controllers\Admin\SettingsController::class, 'databaseBackup'])->name('backup');
        Route::post('/reset', [App\Http\Controllers\Admin\SettingsController::class, 'resetSettings'])->name('reset');
    });

    // ===== SYSTEM & MAINTENANCE =====
    Route::get('/system-info', [AdminDashboardController::class, 'systemInfo'])->name('system-info');
    
}); 