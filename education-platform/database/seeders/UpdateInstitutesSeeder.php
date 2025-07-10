<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Institute;
use Illuminate\Support\Str;

class UpdateInstitutesSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $institutes = Institute::all();
        
        foreach ($institutes as $institute) {
            // Generate slug from institute name
            $slug = Str::slug($institute->institute_name);
            
            // Ensure unique slug
            $originalSlug = $slug;
            $counter = 1;
            while (Institute::where('slug', $slug)->where('id', '!=', $institute->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            // Update institute with missing fields
            $institute->update([
                'slug' => $slug,
                'verification_status' => 'verified',
                'is_featured' => rand(0, 1) == 1,
                'logo' => 'https://ui-avatars.com/api/?name=' . urlencode($institute->institute_name) . '&size=200&background=random',
                'website' => 'https://www.' . Str::slug($institute->institute_name) . '.edu',
                'contact_phone' => '+91-' . rand(7000000000, 9999999999),
                'contact_email' => 'contact@' . Str::slug($institute->institute_name) . '.edu',
            ]);
        }
        
        $this->command->info('Updated ' . $institutes->count() . ' institutes with additional data.');
    }
} 