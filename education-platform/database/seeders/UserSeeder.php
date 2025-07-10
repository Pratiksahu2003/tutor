<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\TeacherProfile;
use App\Models\StudentProfile;
use App\Models\Institute;
use App\Models\Subject;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin only if doesn't exist
        if (!User::where('email', 'admin@educationplatform.com')->exists()) {
            $superAdmin = User::create([
                'name' => 'Super Administrator',
                'email' => 'admin@educationplatform.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '+91-9000000001',
                'address' => 'Admin Office, Education Platform HQ',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400001',
                'country' => 'India',
                'is_active' => true,
                'remember_token' => Str::random(10),
            ]);
        }

        // Get all institutes
        $institutes = Institute::all();

        // Create Teachers
        $teacherNames = [
            ['name' => 'Dr. Rajesh Sharma', 'email' => 'rajesh.sharma@teacher.com', 'specialization' => 'Mathematics', 'experience' => 15],
            ['name' => 'Prof. Priya Patel', 'email' => 'priya.patel@teacher.com', 'specialization' => 'Physics', 'experience' => 12],
            ['name' => 'Ms. Anjali Singh', 'email' => 'anjali.singh@teacher.com', 'specialization' => 'Chemistry', 'experience' => 10],
            ['name' => 'Mr. Suresh Kumar', 'email' => 'suresh.kumar@teacher.com', 'specialization' => 'Biology', 'experience' => 8],
            ['name' => 'Dr. Meera Gupta', 'email' => 'meera.gupta@teacher.com', 'specialization' => 'English', 'experience' => 14],
            ['name' => 'Prof. Arjun Reddy', 'email' => 'arjun.reddy@teacher.com', 'specialization' => 'Computer Science', 'experience' => 11],
            ['name' => 'Ms. Kavitha Rao', 'email' => 'kavitha.rao@teacher.com', 'specialization' => 'History', 'experience' => 9],
            ['name' => 'Mr. Vikram Joshi', 'email' => 'vikram.joshi@teacher.com', 'specialization' => 'Economics', 'experience' => 13],
            ['name' => 'Dr. Sunita Verma', 'email' => 'sunita.verma@teacher.com', 'specialization' => 'Hindi', 'experience' => 16],
            ['name' => 'Prof. Ravi Nair', 'email' => 'ravi.nair@teacher.com', 'specialization' => 'Geography', 'experience' => 7],
        ];

        foreach ($teacherNames as $index => $teacherData) {
            $institute = $institutes->random();
            $teacher = User::create([
                'name' => $teacherData['name'],
                'email' => $teacherData['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'teacher',
                'phone' => '+91-800000001' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'address' => ($index + 100) . ' Teacher Colony, Education District',
                'city' => ['Mumbai', 'Delhi', 'Bangalore', 'Chennai', 'Pune', 'Hyderabad'][$index % 6],
                'state' => ['Maharashtra', 'Delhi', 'Karnataka', 'Tamil Nadu', 'Maharashtra', 'Telangana'][$index % 6],
                'pincode' => '40000' . ($index + 1),
                'country' => 'India',
                'is_active' => true,
                'remember_token' => Str::random(10),
            ]);

            // Create Teacher Profile
            TeacherProfile::create([
                'user_id' => $teacher->id,
                'qualification' => ['M.Sc.', 'Ph.D.', 'M.Tech.', 'M.A.', 'B.Ed.', 'M.Ed.'][$index % 6],
                'bio' => 'Experienced ' . $teacherData['specialization'] . ' teacher with ' . $teacherData['experience'] . ' years of teaching experience.',
                'experience_years' => $teacherData['experience'],
                'hourly_rate' => rand(300, 1500),
                'specialization' => $teacherData['specialization'],
                'languages' => json_encode(['English', 'Hindi']),
                'availability' => 'Weekdays and Weekends',
                'teaching_mode' => ['online', 'offline', 'both'][$index % 3],
                'rating' => round(3.5 + (rand(0, 15) / 10), 1),
                'total_students' => rand(5, 50),
                'verified' => true,
                'teaching_city' => $teacher->city,
                'teaching_state' => $teacher->state,
                'teaching_pincode' => $teacher->pincode,
                'travel_radius_km' => rand(5, 25),
                'home_tuition' => true,
                'institute_classes' => true,
                'online_classes' => true,
            ]);
        }

        // Create Students
        $studentNames = [
            ['name' => 'Aarav Sharma', 'email' => 'aarav.sharma@student.com', 'class' => '12th', 'board' => 'CBSE'],
            ['name' => 'Isha Patel', 'email' => 'isha.patel@student.com', 'class' => '11th', 'board' => 'CBSE'],
            ['name' => 'Rohan Singh', 'email' => 'rohan.singh@student.com', 'class' => '12th', 'board' => 'ICSE'],
            ['name' => 'Ananya Gupta', 'email' => 'ananya.gupta@student.com', 'class' => '10th', 'board' => 'CBSE'],
            ['name' => 'Arjun Kumar', 'email' => 'arjun.kumar@student.com', 'class' => '12th', 'board' => 'State Board'],
            ['name' => 'Priya Reddy', 'email' => 'priya.reddy@student.com', 'class' => '11th', 'board' => 'CBSE'],
            ['name' => 'Vikash Yadav', 'email' => 'vikash.yadav@student.com', 'class' => '9th', 'board' => 'CBSE'],
            ['name' => 'Kavya Joshi', 'email' => 'kavya.joshi@student.com', 'class' => '12th', 'board' => 'ICSE'],
            ['name' => 'Rahul Verma', 'email' => 'rahul.verma@student.com', 'class' => '11th', 'board' => 'CBSE'],
            ['name' => 'Sneha Agarwal', 'email' => 'sneha.agarwal@student.com', 'class' => '10th', 'board' => 'State Board'],
            ['name' => 'Karan Nair', 'email' => 'karan.nair@student.com', 'class' => '12th', 'board' => 'CBSE'],
            ['name' => 'Pooja Tiwari', 'email' => 'pooja.tiwari@student.com', 'class' => '9th', 'board' => 'CBSE'],
            ['name' => 'Amit Mishra', 'email' => 'amit.mishra@student.com', 'class' => '11th', 'board' => 'ICSE'],
            ['name' => 'Riya Bansal', 'email' => 'riya.bansal@student.com', 'class' => '12th', 'board' => 'CBSE'],
            ['name' => 'Harsh Dubey', 'email' => 'harsh.dubey@student.com', 'class' => '10th', 'board' => 'State Board'],
        ];

        $subjects = Subject::all();

        foreach ($studentNames as $index => $studentData) {
            $student = User::create([
                'name' => $studentData['name'],
                'email' => $studentData['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'student',
                'phone' => '+91-700000001' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'address' => ($index + 200) . ' Student Housing, Education City',
                'city' => ['Mumbai', 'Delhi', 'Bangalore', 'Chennai', 'Pune', 'Hyderabad'][$index % 6],
                'state' => ['Maharashtra', 'Delhi', 'Karnataka', 'Tamil Nadu', 'Maharashtra', 'Telangana'][$index % 6],
                'pincode' => '50000' . ($index + 1),
                'country' => 'India',
                'is_active' => true,
                'remember_token' => Str::random(10),
            ]);

            // Create Student Profile
            $interestedSubjects = $subjects->random(3)->pluck('id')->toArray();
            
            StudentProfile::create([
                'user_id' => $student->id,
                'date_of_birth' => '200' . (4 + ($index % 6)) . '-0' . (($index % 11) + 1) . '-15',
                'gender' => $index % 2 == 0 ? 'male' : 'female',
                'current_class' => $studentData['class'],
                'school_name' => 'Demo School ' . ($index + 1),
                'board' => $studentData['board'],
                'city' => $student->city,
                'state' => $student->state,
                'pincode' => $student->pincode,
                'address' => $student->address,
                'subjects_interested' => json_encode($interestedSubjects),
                'learning_goals' => json_encode(['Improve grades', 'Exam preparation', 'Concept clarity']),
                'preferred_learning_mode' => ['online', 'offline', 'both'][$index % 3],
                'budget_min' => 500.00,
                'budget_max' => 2000.00,
                'parent_phone' => '+91-600000001' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'emergency_contact' => '+91-600000001' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'is_active' => true,
                'profile_completed' => true,
                'last_activity' => now(),
            ]);
        }

        // Create some regular users (parents/guardians)
        $parentNames = [
            'Mr. Rajesh Sharma Sr.',
            'Mrs. Sunita Patel',
            'Mr. Vinod Singh',
            'Mrs. Meera Gupta',
            'Mr. Suresh Kumar Sr.',
        ];

        foreach ($parentNames as $index => $parentName) {
            User::create([
                'name' => $parentName,
                'email' => 'parent' . ($index + 1) . '@parent.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'student', // Changed from 'user' to 'student' as 'user' is not allowed
                'phone' => '+91-600000010' . ($index + 1),
                'address' => ($index + 300) . ' Parent Residence, Family Colony',
                'city' => ['Mumbai', 'Delhi', 'Bangalore', 'Chennai', 'Pune'][$index % 5],
                'state' => ['Maharashtra', 'Delhi', 'Karnataka', 'Tamil Nadu', 'Maharashtra'][$index % 5],
                'pincode' => '60000' . ($index + 1),
                'country' => 'India',
                'is_active' => true,
                'remember_token' => Str::random(10),
            ]);
        }
    }
}
