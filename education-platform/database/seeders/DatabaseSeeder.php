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
        // Clear existing data (optional - be careful in production)
        // User::query()->delete();
        
        $this->call([
            SubjectSeeder::class,
            InstituteSeeder::class,
            UserSeeder::class,
        ]);

        $this->command->info('Database seeded successfully with comprehensive dummy data!');
        $this->command->info('Available login credentials:');
        $this->command->info('Admin: admin@educationplatform.com / password123');
        $this->command->info('Institute Users: Check institute emails with password123');
        $this->command->info('Teachers: Check teacher emails with password123');
        $this->command->info('Students: Check student emails with password123');
        $this->command->info('Parents: parent1@parent.com to parent5@parent.com / password123');
    }
}
