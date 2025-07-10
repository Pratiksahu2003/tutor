<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::getAllGrouped();
        $groups = [
            'general' => 'General Settings',
            'site' => 'Site Configuration', 
            'cache' => 'Cache Management',
            'database' => 'Database Settings',
            'social' => 'Social Media',
            'content' => 'Content Management',
            'appearance' => 'Appearance',
            'system' => 'System Settings'
        ];

        return view('admin.settings.index', compact('settings', 'groups'));
    }

    public function update(Request $request)
    {
        try {
            $settings = $request->except(['_token', '_method']);
            
            foreach ($settings as $key => $value) {
                $setting = SiteSetting::where('key', $key)->first();
                
                if ($setting) {
                    // Handle file uploads
                    if ($setting->type === 'file' && $request->hasFile($key)) {
                        $file = $request->file($key);
                        $path = $file->store('uploads/settings', 'public');
                        $value = $path;
                        
                        // Delete old file if exists
                        if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                            Storage::disk('public')->delete($setting->value);
                        }
                    }
                    
                    // Handle boolean values
                    if ($setting->type === 'boolean') {
                        $value = $request->has($key) ? '1' : '0';
                    }
                    
                    // Handle JSON values
                    if ($setting->type === 'json' && is_string($value)) {
                        $decoded = json_decode($value, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $value = $value;
                        }
                    }
                    
                    $setting->update(['value' => $value]);
                }
            }
            
            // Clear cache
            SiteSetting::clearCache();
            
            return redirect()->back()->with('success', 'Settings updated successfully!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating settings: ' . $e->getMessage());
        }
    }

    public function clearCache(Request $request)
    {
        try {
            $cacheType = $request->get('type', 'all');
            
            switch ($cacheType) {
                case 'config':
                    Artisan::call('config:clear');
                    break;
                case 'route':
                    Artisan::call('route:clear');
                    break;
                case 'view':
                    Artisan::call('view:clear');
                    break;
                case 'cache':
                    Artisan::call('cache:clear');
                    break;
                case 'all':
                default:
                    Artisan::call('cache:clear');
                    Artisan::call('config:clear');
                    Artisan::call('route:clear');
                    Artisan::call('view:clear');
                    break;
            }
            
            return response()->json(['success' => true, 'message' => 'Cache cleared successfully!']);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error clearing cache: ' . $e->getMessage()]);
        }
    }

    public function databaseBackup()
    {
        try {
            $filename = 'backup_' . date('Y_m_d_H_i_s') . '.sql';
            $path = storage_path('app/backups');
            
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }
            
            $fullPath = $path . '/' . $filename;
            
            // Get database configuration
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            
            // Create mysqldump command
            $command = "mysqldump -h {$host} -u {$username} -p{$password} {$database} > {$fullPath}";
            
            // Execute backup
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0) {
                return response()->download($fullPath)->deleteFileAfterSend(true);
            } else {
                throw new \Exception('Backup failed');
            }
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Backup failed: ' . $e->getMessage()]);
        }
    }

    public function systemInfo()
    {
        $info = [
            'PHP Version' => phpversion(),
            'Laravel Version' => app()->version(),
            'Server Software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'Database' => config('database.default'),
            'Cache Driver' => config('cache.default'),
            'Queue Driver' => config('queue.default'),
            'Mail Driver' => config('mail.default'),
            'Environment' => app()->environment(),
            'Debug Mode' => config('app.debug') ? 'Enabled' : 'Disabled',
            'Memory Limit' => ini_get('memory_limit'),
            'Max Execution Time' => ini_get('max_execution_time') . 's',
            'Upload Max Size' => ini_get('upload_max_filesize'),
            'Disk Space' => $this->formatBytes(disk_free_space('/')),
        ];

        return response()->json($info);
    }

    public function optimizeApplication()
    {
        try {
            // Run optimization commands
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            
            return response()->json(['success' => true, 'message' => 'Application optimized successfully!']);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Optimization failed: ' . $e->getMessage()]);
        }
    }

    public function resetSettings()
    {
        try {
            // Reset to default settings
            $this->seedDefaultSettings();
            SiteSetting::clearCache();
            
            return redirect()->back()->with('success', 'Settings reset to defaults successfully!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error resetting settings: ' . $e->getMessage());
        }
    }

    private function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');
        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }

    private function seedDefaultSettings()
    {
        $defaultSettings = [
            // General Settings
            ['key' => 'site_name', 'value' => 'Education Platform', 'type' => 'text', 'group' => 'general', 'label' => 'Site Name', 'description' => 'The name of your website'],
            ['key' => 'site_description', 'value' => 'A comprehensive education platform', 'type' => 'textarea', 'group' => 'general', 'label' => 'Site Description', 'description' => 'Brief description of your website'],
            ['key' => 'site_keywords', 'value' => 'education, learning, platform', 'type' => 'text', 'group' => 'general', 'label' => 'Site Keywords', 'description' => 'SEO keywords for your website'],
            ['key' => 'admin_email', 'value' => 'admin@educationplatform.com', 'type' => 'text', 'group' => 'general', 'label' => 'Admin Email', 'description' => 'Primary admin email address'],
            ['key' => 'contact_phone', 'value' => '+1234567890', 'type' => 'text', 'group' => 'general', 'label' => 'Contact Phone', 'description' => 'Contact phone number'],
            ['key' => 'contact_address', 'value' => '123 Education Street, Learning City', 'type' => 'textarea', 'group' => 'general', 'label' => 'Contact Address', 'description' => 'Physical address'],
            
            // Site Configuration
            ['key' => 'site_logo', 'value' => '', 'type' => 'file', 'group' => 'site', 'label' => 'Site Logo', 'description' => 'Main logo for the website'],
            ['key' => 'favicon', 'value' => '', 'type' => 'file', 'group' => 'site', 'label' => 'Favicon', 'description' => 'Website favicon'],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'site', 'label' => 'Maintenance Mode', 'description' => 'Enable maintenance mode'],
            ['key' => 'user_registration', 'value' => '1', 'type' => 'boolean', 'group' => 'site', 'label' => 'User Registration', 'description' => 'Allow new user registration'],
            ['key' => 'email_verification', 'value' => '1', 'type' => 'boolean', 'group' => 'site', 'label' => 'Email Verification', 'description' => 'Require email verification'],
            
            // Social Media
            ['key' => 'facebook_url', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'Facebook URL', 'description' => 'Facebook page URL'],
            ['key' => 'twitter_url', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'Twitter URL', 'description' => 'Twitter profile URL'],
            ['key' => 'instagram_url', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'Instagram URL', 'description' => 'Instagram profile URL'],
            ['key' => 'linkedin_url', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'LinkedIn URL', 'description' => 'LinkedIn profile URL'],
            ['key' => 'youtube_url', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'YouTube URL', 'description' => 'YouTube channel URL'],
            
            // Content Management
            ['key' => 'privacy_policy', 'value' => 'Your privacy policy content here...', 'type' => 'textarea', 'group' => 'content', 'label' => 'Privacy Policy', 'description' => 'Privacy policy content'],
            ['key' => 'terms_conditions', 'value' => 'Your terms and conditions content here...', 'type' => 'textarea', 'group' => 'content', 'label' => 'Terms & Conditions', 'description' => 'Terms and conditions content'],
            ['key' => 'about_us', 'value' => 'About us content here...', 'type' => 'textarea', 'group' => 'content', 'label' => 'About Us', 'description' => 'About us page content'],
            ['key' => 'contact_us_text', 'value' => 'Contact us for any inquiries...', 'type' => 'textarea', 'group' => 'content', 'label' => 'Contact Us Text', 'description' => 'Contact page additional text'],
            
            // System Settings
            ['key' => 'items_per_page', 'value' => '10', 'type' => 'number', 'group' => 'system', 'label' => 'Items Per Page', 'description' => 'Number of items to show per page'],
            ['key' => 'session_timeout', 'value' => '120', 'type' => 'number', 'group' => 'system', 'label' => 'Session Timeout (minutes)', 'description' => 'User session timeout in minutes'],
            ['key' => 'max_upload_size', 'value' => '2048', 'type' => 'number', 'group' => 'system', 'label' => 'Max Upload Size (KB)', 'description' => 'Maximum file upload size'],
            ['key' => 'backup_frequency', 'value' => 'weekly', 'type' => 'text', 'group' => 'system', 'label' => 'Backup Frequency', 'description' => 'Automatic backup frequency'],
        ];

        foreach ($defaultSettings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
} 