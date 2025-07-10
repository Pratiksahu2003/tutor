<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // User Management
            [
                'name' => 'View Users',
                'slug' => 'view-users',
                'description' => 'Can view user listings',
                'category' => 'User Management',
                'module' => 'users'
            ],
            [
                'name' => 'Create Users',
                'slug' => 'create-users',
                'description' => 'Can create new users',
                'category' => 'User Management',
                'module' => 'users'
            ],
            [
                'name' => 'Edit Users',
                'slug' => 'edit-users',
                'description' => 'Can edit existing users',
                'category' => 'User Management',
                'module' => 'users'
            ],
            [
                'name' => 'Delete Users',
                'slug' => 'delete-users',
                'description' => 'Can delete users',
                'category' => 'User Management',
                'module' => 'users'
            ],
            [
                'name' => 'Manage User Roles',
                'slug' => 'manage-user-roles',
                'description' => 'Can assign and remove roles from users',
                'category' => 'User Management',
                'module' => 'users'
            ],

            // Role Management
            [
                'name' => 'View Roles',
                'slug' => 'view-roles',
                'description' => 'Can view role listings',
                'category' => 'Role Management',
                'module' => 'roles'
            ],
            [
                'name' => 'Create Roles',
                'slug' => 'create-roles',
                'description' => 'Can create new roles',
                'category' => 'Role Management',
                'module' => 'roles'
            ],
            [
                'name' => 'Edit Roles',
                'slug' => 'edit-roles',
                'description' => 'Can edit existing roles',
                'category' => 'Role Management',
                'module' => 'roles'
            ],
            [
                'name' => 'Delete Roles',
                'slug' => 'delete-roles',
                'description' => 'Can delete roles',
                'category' => 'Role Management',
                'module' => 'roles'
            ],

            // Permission Management
            [
                'name' => 'View Permissions',
                'slug' => 'view-permissions',
                'description' => 'Can view permission listings',
                'category' => 'Permission Management',
                'module' => 'permissions'
            ],
            [
                'name' => 'Create Permissions',
                'slug' => 'create-permissions',
                'description' => 'Can create new permissions',
                'category' => 'Permission Management',
                'module' => 'permissions'
            ],
            [
                'name' => 'Edit Permissions',
                'slug' => 'edit-permissions',
                'description' => 'Can edit existing permissions',
                'category' => 'Permission Management',
                'module' => 'permissions'
            ],
            [
                'name' => 'Delete Permissions',
                'slug' => 'delete-permissions',
                'description' => 'Can delete permissions',
                'category' => 'Permission Management',
                'module' => 'permissions'
            ],
            [
                'name' => 'Assign Permissions',
                'slug' => 'assign-permissions',
                'description' => 'Can assign permissions to roles',
                'category' => 'Permission Management',
                'module' => 'permissions'
            ],

            // Teacher Management
            [
                'name' => 'View Teachers',
                'slug' => 'view-teachers',
                'description' => 'Can view teacher profiles',
                'category' => 'Teacher Management',
                'module' => 'teachers'
            ],
            [
                'name' => 'Approve Teachers',
                'slug' => 'approve-teachers',
                'description' => 'Can approve teacher registrations',
                'category' => 'Teacher Management',
                'module' => 'teachers'
            ],
            [
                'name' => 'Manage Teachers',
                'slug' => 'manage-teachers',
                'description' => 'Can manage teacher profiles and settings',
                'category' => 'Teacher Management',
                'module' => 'teachers'
            ],
            [
                'name' => 'Verify Teachers',
                'slug' => 'verify-teachers',
                'description' => 'Can verify teacher credentials',
                'category' => 'Teacher Management',
                'module' => 'teachers'
            ],

            // Institute Management
            [
                'name' => 'View Institutes',
                'slug' => 'view-institutes',
                'description' => 'Can view institute profiles',
                'category' => 'Institute Management',
                'module' => 'institutes'
            ],
            [
                'name' => 'Approve Institutes',
                'slug' => 'approve-institutes',
                'description' => 'Can approve institute registrations',
                'category' => 'Institute Management',
                'module' => 'institutes'
            ],
            [
                'name' => 'Manage Institutes',
                'slug' => 'manage-institutes',
                'description' => 'Can manage institute profiles and settings',
                'category' => 'Institute Management',
                'module' => 'institutes'
            ],
            [
                'name' => 'Verify Institutes',
                'slug' => 'verify-institutes',
                'description' => 'Can verify institute credentials',
                'category' => 'Institute Management',
                'module' => 'institutes'
            ],

            // Subject Management
            [
                'name' => 'View Subjects',
                'slug' => 'view-subjects',
                'description' => 'Can view subject listings',
                'category' => 'Subject Management',
                'module' => 'subjects'
            ],
            [
                'name' => 'Create Subjects',
                'slug' => 'create-subjects',
                'description' => 'Can create new subjects',
                'category' => 'Subject Management',
                'module' => 'subjects'
            ],
            [
                'name' => 'Edit Subjects',
                'slug' => 'edit-subjects',
                'description' => 'Can edit existing subjects',
                'category' => 'Subject Management',
                'module' => 'subjects'
            ],
            [
                'name' => 'Delete Subjects',
                'slug' => 'delete-subjects',
                'description' => 'Can delete subjects',
                'category' => 'Subject Management',
                'module' => 'subjects'
            ],

            // System Administration
            [
                'name' => 'View Dashboard',
                'slug' => 'view-dashboard',
                'description' => 'Can access admin dashboard',
                'category' => 'System Administration',
                'module' => 'admin'
            ],
            [
                'name' => 'System Settings',
                'slug' => 'system-settings',
                'description' => 'Can manage system settings',
                'category' => 'System Administration',
                'module' => 'admin'
            ],
            [
                'name' => 'View Reports',
                'slug' => 'view-reports',
                'description' => 'Can view system reports',
                'category' => 'System Administration',
                'module' => 'admin'
            ],
            [
                'name' => 'Manage Content',
                'slug' => 'manage-content',
                'description' => 'Can manage site content',
                'category' => 'System Administration',
                'module' => 'admin'
            ],

            // Student Management
            [
                'name' => 'View Students',
                'slug' => 'view-students',
                'description' => 'Can view student profiles',
                'category' => 'Student Management',
                'module' => 'students'
            ],
            [
                'name' => 'Manage Students',
                'slug' => 'manage-students',
                'description' => 'Can manage student accounts',
                'category' => 'Student Management',
                'module' => 'students'
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }
    }
}
