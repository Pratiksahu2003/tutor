<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class SyncUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:sync-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync user roles from the role field to the user_roles pivot table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting user role synchronization...');

        // Get all roles
        $roles = Role::all()->keyBy('name');
        
        // Get all users
        $users = User::all();
        
        $syncedCount = 0;
        $errors = [];

        foreach ($users as $user) {
            try {
                // Get the role name from the user's role field
                $roleName = $user->role;
                
                if ($roleName && isset($roles[$roleName])) {
                    $role = $roles[$roleName];
                    
                    // Check if the relationship already exists
                    $exists = DB::table('user_roles')
                        ->where('user_id', $user->id)
                        ->where('role_id', $role->id)
                        ->exists();
                    
                    if (!$exists) {
                        // Create the relationship
                        DB::table('user_roles')->insert([
                            'user_id' => $user->id,
                            'role_id' => $role->id,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                        
                        $syncedCount++;
                        $this->line("✓ Synced user {$user->name} ({$user->email}) to role {$roleName}");
                    } else {
                        $this->line("- User {$user->name} already has role {$roleName}");
                    }
                } else {
                    $errors[] = "User {$user->name} ({$user->email}) has invalid role: {$roleName}";
                    $this->error("✗ Invalid role '{$roleName}' for user {$user->name}");
                }
            } catch (\Exception $e) {
                $errors[] = "Error syncing user {$user->name}: " . $e->getMessage();
                $this->error("✗ Error syncing user {$user->name}: " . $e->getMessage());
            }
        }

        $this->info("\nSynchronization completed!");
        $this->info("Successfully synced: {$syncedCount} users");
        
        if (!empty($errors)) {
            $this->error("Errors encountered: " . count($errors));
            foreach ($errors as $error) {
                $this->error("- {$error}");
            }
        }

        // Show final counts
        $totalUsers = User::count();
        $totalUserRoles = DB::table('user_roles')->count();
        
        $this->info("\nFinal counts:");
        $this->info("- Total users: {$totalUsers}");
        $this->info("- Total user-role relationships: {$totalUserRoles}");

        return 0;
    }
} 