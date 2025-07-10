<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\CMSController;
// use App\Http\Controllers\Admin\LeadManagementController;
// use App\Http\Controllers\Admin\AnalyticsController;
// use App\Http\Controllers\Admin\SettingsController;
// use App\Http\Controllers\Admin\ContentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| All admin-related routes with proper permissions and middleware
|
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Main Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->middleware('permission:view-dashboard')
        ->name('dashboard');
    
    // Dashboard API routes
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/stats', [AdminDashboardController::class, 'getStats'])
            ->middleware('permission:view-dashboard');
        Route::get('/activity', [AdminDashboardController::class, 'getRecentActivity'])
            ->middleware('permission:view-dashboard');
        // Route::get('/analytics', [AnalyticsController::class, 'getAnalytics'])
        //     ->middleware('permission:view-dashboard');
    });
    
    // User Management Routes
    Route::prefix('users')->name('users.')->middleware('permission:view-users')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/{user}', [UserManagementController::class, 'show'])->name('show');
        
        Route::middleware('permission:create-users')->group(function () {
            Route::get('/create', [UserManagementController::class, 'create'])->name('create');
            Route::post('/', [UserManagementController::class, 'store'])->name('store');
        });
        
        Route::middleware('permission:edit-users')->group(function () {
            Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
        });
        
        Route::middleware('permission:delete-users')->group(function () {
            Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
        });
        
        Route::middleware('permission:manage-user-roles')->group(function () {
            Route::post('/{user}/roles', [UserManagementController::class, 'assignRole'])->name('assign-role');
            Route::delete('/{user}/roles/{role}', [UserManagementController::class, 'removeRole'])->name('remove-role');
        });
    });
    
    // Role Management Routes
    Route::middleware('permission:view-roles')->group(function () {
        Route::resource('roles', RoleController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
        
        Route::middleware('permission:create-roles')->group(function () {
            Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
            Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        });
        
        Route::middleware('permission:edit-roles')->group(function () {
            Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
            Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        });
        
        Route::middleware('permission:delete-roles')->group(function () {
            Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
        });
        
        Route::middleware('permission:assign-permissions')->group(function () {
            Route::post('/roles/{role}/permissions', [RoleController::class, 'assignPermission'])->name('roles.assign-permission');
            Route::delete('/roles/{role}/permissions/{permission}', [RoleController::class, 'removePermission'])->name('roles.remove-permission');
        });
    });
    
    // Permission Management Routes
    Route::middleware('permission:view-permissions')->group(function () {
        Route::resource('permissions', PermissionController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
        
        Route::middleware('permission:create-permissions')->group(function () {
            Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
            Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
        });
        
        Route::middleware('permission:edit-permissions')->group(function () {
            Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
            Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
        });
        
        Route::middleware('permission:delete-permissions')->group(function () {
            Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
        });
    });
    
    // CMS Management Routes
    Route::prefix('cms')->name('cms.')->middleware('permission:manage-content')->group(function () {
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
        
        // Menu Management
        Route::prefix('menus')->name('menus.')->group(function () {
            Route::get('/', [CMSController::class, 'menus'])->name('index');
            Route::get('/create', [CMSController::class, 'createMenu'])->name('create');
            Route::post('/', [CMSController::class, 'storeMenu'])->name('store');
            Route::get('/{menu}/edit', [CMSController::class, 'editMenu'])->name('edit');
            Route::put('/{menu}', [CMSController::class, 'updateMenu'])->name('update');
            Route::delete('/{menu}', [CMSController::class, 'destroyMenu'])->name('destroy');
        });
        
        // Slider Management
        Route::prefix('sliders')->name('sliders.')->group(function () {
            Route::get('/', [CMSController::class, 'sliders'])->name('index');
            Route::get('/create', [CMSController::class, 'createSlider'])->name('create');
            Route::post('/', [CMSController::class, 'storeSlider'])->name('store');
            Route::get('/{slider}/edit', [CMSController::class, 'editSlider'])->name('edit');
            Route::put('/{slider}', [CMSController::class, 'updateSlider'])->name('update');
            Route::delete('/{slider}', [CMSController::class, 'destroySlider'])->name('destroy');
        });
    });
    
    /*
    // Lead Management Routes - COMMENTED OUT DUE TO MISSING CONTROLLER
    Route::prefix('leads')->name('leads.')->middleware('permission:manage-leads')->group(function () {
        Route::get('/', [LeadManagementController::class, 'index'])->name('index');
        Route::get('/{lead}', [LeadManagementController::class, 'show'])->name('show');
        Route::put('/{lead}/status', [LeadManagementController::class, 'updateStatus'])->name('update-status');
        Route::post('/{lead}/assign', [LeadManagementController::class, 'assignTo'])->name('assign');
        Route::post('/{lead}/note', [LeadManagementController::class, 'addNote'])->name('add-note');
        Route::delete('/{lead}', [LeadManagementController::class, 'destroy'])->name('destroy');
        Route::get('/export/csv', [LeadManagementController::class, 'exportCSV'])->name('export-csv');
        Route::get('/analytics/overview', [LeadManagementController::class, 'analytics'])->name('analytics');
    });
    
    // Analytics & Reports Routes - COMMENTED OUT DUE TO MISSING CONTROLLER
    Route::prefix('analytics')->name('analytics.')->middleware('permission:view-reports')->group(function () {
        Route::get('/', [AnalyticsController::class, 'index'])->name('index');
        Route::get('/users', [AnalyticsController::class, 'userAnalytics'])->name('users');
        Route::get('/institutes', [AnalyticsController::class, 'instituteAnalytics'])->name('institutes');
        Route::get('/teachers', [AnalyticsController::class, 'teacherAnalytics'])->name('teachers');
        Route::get('/leads', [AnalyticsController::class, 'leadAnalytics'])->name('leads');
        Route::get('/performance', [AnalyticsController::class, 'performanceMetrics'])->name('performance');
        Route::get('/financial', [AnalyticsController::class, 'financialReports'])->name('financial');
    });
    
    // Settings Routes - COMMENTED OUT DUE TO MISSING CONTROLLER
    Route::prefix('settings')->name('settings.')->middleware('permission:manage-settings')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::get('/general', [SettingsController::class, 'general'])->name('general');
        Route::put('/general', [SettingsController::class, 'updateGeneral'])->name('general.update');
        Route::get('/email', [SettingsController::class, 'email'])->name('email');
        Route::put('/email', [SettingsController::class, 'updateEmail'])->name('email.update');
        Route::get('/payment', [SettingsController::class, 'payment'])->name('payment');
        Route::put('/payment', [SettingsController::class, 'updatePayment'])->name('payment.update');
        Route::get('/seo', [SettingsController::class, 'seo'])->name('seo');
        Route::put('/seo', [SettingsController::class, 'updateSeo'])->name('seo.update');
        Route::get('/social', [SettingsController::class, 'social'])->name('social');
        Route::put('/social', [SettingsController::class, 'updateSocial'])->name('social.update');
    });
    */
    
    // Additional maintenance and utility routes
    Route::get('/system-info', [AdminDashboardController::class, 'systemInfo'])
        ->middleware('permission:view-dashboard')
        ->name('system-info');
}); 