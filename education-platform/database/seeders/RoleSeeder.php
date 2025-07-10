<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Full system access with all permissions',
                'sort_order' => 1
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrative access to manage platform',
                'sort_order' => 2
            ],
            [
                'name' => 'Moderator',
                'slug' => 'moderator',
                'description' => 'Can moderate content and approve users',
                'sort_order' => 3
            ],
            [
                'name' => 'Teacher Manager',
                'slug' => 'teacher-manager',
                'description' => 'Can manage teacher-related operations',
                'sort_order' => 4
            ],
            [
                'name' => 'Institute Manager',
                'slug' => 'institute-manager',
                'description' => 'Can manage institute-related operations',
                'sort_order' => 5
            ],
            [
                'name' => 'Content Manager',
                'slug' => 'content-manager',
                'description' => 'Can manage subjects and educational content',
                'sort_order' => 6
            ],
            [
                'name' => 'Student Support',
                'slug' => 'student-support',
                'description' => 'Can assist students and handle support requests',
                'sort_order' => 7
            ]
        ];

        foreach ($roles as $roleData) {
            $role = Role::firstOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );

            // Assign permissions based on role
            $this->assignPermissionsToRole($role);
        }
    }

    private function assignPermissionsToRole(Role $role)
    {
        switch ($role->slug) {
            case 'super-admin':
                // Super admin gets all permissions
                $permissions = Permission::all();
                $role->permissions()->sync($permissions->pluck('id'));
                break;

            case 'admin':
                // Admin gets most permissions except super admin specific ones
                $permissions = Permission::whereNotIn('slug', [
                    'delete-permissions',
                    'system-settings'
                ])->get();
                $role->permissions()->sync($permissions->pluck('id'));
                break;

            case 'moderator':
                $permissions = Permission::whereIn('slug', [
                    'view-dashboard',
                    'view-users',
                    'view-teachers',
                    'approve-teachers',
                    'verify-teachers',
                    'view-institutes',
                    'approve-institutes',
                    'verify-institutes',
                    'view-students',
                    'manage-content'
                ])->get();
                $role->permissions()->sync($permissions->pluck('id'));
                break;

            case 'teacher-manager':
                $permissions = Permission::whereIn('slug', [
                    'view-dashboard',
                    'view-teachers',
                    'approve-teachers',
                    'manage-teachers',
                    'verify-teachers',
                    'view-subjects'
                ])->get();
                $role->permissions()->sync($permissions->pluck('id'));
                break;

            case 'institute-manager':
                $permissions = Permission::whereIn('slug', [
                    'view-dashboard',
                    'view-institutes',
                    'approve-institutes',
                    'manage-institutes',
                    'verify-institutes',
                    'view-subjects'
                ])->get();
                $role->permissions()->sync($permissions->pluck('id'));
                break;

            case 'content-manager':
                $permissions = Permission::whereIn('slug', [
                    'view-dashboard',
                    'view-subjects',
                    'create-subjects',
                    'edit-subjects',
                    'delete-subjects',
                    'manage-content'
                ])->get();
                $role->permissions()->sync($permissions->pluck('id'));
                break;

            case 'student-support':
                $permissions = Permission::whereIn('slug', [
                    'view-dashboard',
                    'view-students',
                    'manage-students',
                    'view-teachers',
                    'view-institutes'
                ])->get();
                $role->permissions()->sync($permissions->pluck('id'));
                break;
        }
    }
}
