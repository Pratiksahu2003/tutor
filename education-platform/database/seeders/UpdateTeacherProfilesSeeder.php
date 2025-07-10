<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TeacherProfile;
use App\Models\Subject;
use App\Models\Institute;
use Illuminate\Support\Str;

class UpdateTeacherProfilesSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $subjects = Subject::all();
        $institutes = Institute::all();
        
        $teachers = TeacherProfile::with('user')->get();
        
        foreach ($teachers as $teacher) {
            // Map specialization to subject
            $subjectMapping = [
                'Mathematics' => 'Mathematics',
                'Physics' => 'Physics', 
                'Chemistry' => 'Chemistry',
                'Biology' => 'Biology',
                'English' => 'English',
                'Computer Science' => 'Computer Science',
                'History' => 'History',
                'Economics' => 'Economics',
                'Hindi' => 'Hindi',
                'Geography' => 'Geography',
            ];
            
            $subjectName = $subjectMapping[$teacher->specialization] ?? 'Mathematics';
            $subject = $subjects->where('name', $subjectName)->first();
            
            // Generate slug from user name
            $slug = $teacher->user ? Str::slug($teacher->user->name) : Str::slug('teacher-' . $teacher->id);
            
            // Ensure unique slug
            $originalSlug = $slug;
            $counter = 1;
            while (TeacherProfile::where('slug', $slug)->where('id', '!=', $teacher->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            // Update teacher profile
            $teacher->update([
                'subject_id' => $subject ? $subject->id : null,
                'slug' => $slug,
                'verification_status' => $teacher->verified ? 'verified' : 'pending',
                'availability_status' => ['available', 'busy', 'offline'][rand(0, 2)],
                'is_featured' => rand(0, 1) == 1,
                'city' => $teacher->teaching_city ?: $teacher->user->city,
                'state' => $teacher->teaching_state ?: $teacher->user->state,
                'qualifications' => $teacher->qualification,
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($teacher->user->name ?? 'Teacher') . '&size=200&background=random',
            ]);
            
            // Assign to random institute for some teachers
            if (!$teacher->institute_id && rand(0, 1) == 1 && $institutes->count() > 0) {
                $randomInstitute = $institutes->random();
                $teacher->update([
                    'institute_id' => $randomInstitute->id,
                    'employment_type' => 'institute',
                    'is_institute_verified' => true,
                ]);
            }
        }
        
        $this->command->info('Updated ' . $teachers->count() . ' teacher profiles with subjects and additional data.');
    }
} 