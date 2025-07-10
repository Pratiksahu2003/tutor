<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@educationplatform.com'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@educationplatform.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Assign super admin role to the user
        $superAdminRole = Role::where('slug', 'super-admin')->first();
        if ($superAdminRole && !$admin->hasRole('super-admin')) {
            $admin->assignRole($superAdminRole, $admin->id);
        }

        // Create additional admin users for testing
        $users = [
            [
                'name' => 'Content Manager',
                'email' => 'content@educationplatform.com',
                'role' => 'admin',
                'assigned_role' => 'content-manager'
            ],
            [
                'name' => 'Teacher Manager',
                'email' => 'teachers@educationplatform.com',
                'role' => 'admin',
                'assigned_role' => 'teacher-manager'
            ],
            [
                'name' => 'Institute Manager',
                'email' => 'institutes@educationplatform.com',
                'role' => 'admin',
                'assigned_role' => 'institute-manager'
            ],
            [
                'name' => 'Moderator',
                'email' => 'moderator@educationplatform.com',
                'role' => 'admin',
                'assigned_role' => 'moderator'
            ]
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make('password123'),
                    'role' => $userData['role'],
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            // Assign specific role
            $role = Role::where('slug', $userData['assigned_role'])->first();
            if ($role && !$user->hasRole($userData['assigned_role'])) {
                $user->assignRole($role, $admin->id);
            }
        }

        $this->command->info('Admin users created successfully!');
        $this->command->info('Email: admin@educationplatform.com');
        $this->command->info('Password: password123');
    }
}
