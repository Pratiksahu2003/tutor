<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run seeders in correct order
        $this->call([
            // User and permission seeders
            PermissionSeeder::class,
            RoleSeeder::class,
            AdminUserSeeder::class,
            
            // Exam system seeders
            ExamCategorySeeder::class,
            SubjectSeeder::class,
            ExamSeeder::class,
        ]);

        // Create test users if needed
        // User::factory(10)->create();

        $this->command->info('Database seeding completed successfully!');
    }
}
