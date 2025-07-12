<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class TestRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test role functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing role functionality...');

        // Test 1: Check all roles
        $this->info("\n1. All Roles:");
        $roles = Role::with('users')->get();
        foreach ($roles as $role) {
            $this->line("- {$role->name}: {$role->users->count()} users");
        }

        // Test 2: Check admin user
        $this->info("\n2. Admin User Test:");
        $adminUser = User::where('email', 'admin@educonnect.com')->first();
        if ($adminUser) {
            $this->line("Admin user found: {$adminUser->name}");
            $this->line("Admin user roles: " . $adminUser->roles->pluck('name')->implode(', '));
            $this->line("Is admin: " . ($adminUser->isAdmin() ? 'Yes' : 'No'));
        } else {
            $this->error("Admin user not found!");
        }

        // Test 3: Check role relationships
        $this->info("\n3. Role Relationships:");
        $userRoleCount = DB::table('user_roles')->count();
        $this->line("Total user-role relationships: {$userRoleCount}");

        // Test 4: Check specific role users
        $this->info("\n4. Users by Role:");
        foreach ($roles as $role) {
            $users = $role->users;
            $this->line("\n{$role->name} users:");
            foreach ($users as $user) {
                $this->line("  - {$user->name} ({$user->email})");
            }
        }

        $this->info("\nRole testing completed!");
        return 0;
    }
} 