<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CompleteEducationPlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Roles and Permissions
        $this->createRolesAndPermissions();
        
        // 2. Create Site Settings
        $this->createSiteSettings();
        
        // 3. Create Subjects
        $this->createSubjects();
        
        // 4. Create Exam Categories and Exams
        $this->createExamsAndCategories();
        
        // 5. Create Admin User
        $this->createAdminUser();
        
        // 6. Create Sample Teachers
        $this->createSampleTeachers();
        
        // 7. Create Sample Institutes
        $this->createSampleInstitutes();
        
        // 8. Create Sample Students
        $this->createSampleStudents();
        
        // 9. Create Sample Questions
        $this->createSampleQuestions();
        
        // 10. Create Sample Pages and Content
        $this->createSampleContent();
        
        $this->command->info('Education Platform database seeded successfully!');
        $this->command->info('Admin Login: admin@educonnect.com / admin123');
        $this->command->info('Teacher Login: rajesh.kumar@example.com / teacher123');
        $this->command->info('Institute Login: info@excellenceacademy.com / institute123');
        $this->command->info('Student Login: rahul.verma@example.com / student123');
    }

    private function createRolesAndPermissions()
    {
        $this->command->info('Creating roles and permissions...');
        
        // Create Roles
        $roles = [
            ['name' => 'admin', 'display_name' => 'Administrator', 'description' => 'Full system access'],
            ['name' => 'teacher', 'display_name' => 'Teacher', 'description' => 'Teacher profile management'],
            ['name' => 'institute', 'display_name' => 'Institute', 'description' => 'Institute management'],
            ['name' => 'student', 'display_name' => 'Student', 'description' => 'Student profile and learning'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insertOrIgnore($role + ['created_at' => now(), 'updated_at' => now()]);
        }

        // Create Permissions
        $permissions = [
            ['name' => 'manage_users', 'display_name' => 'Manage Users', 'module' => 'users'],
            ['name' => 'manage_teachers', 'display_name' => 'Manage Teachers', 'module' => 'teachers'],
            ['name' => 'manage_institutes', 'display_name' => 'Manage Institutes', 'module' => 'institutes'],
            ['name' => 'manage_students', 'display_name' => 'Manage Students', 'module' => 'students'],
            ['name' => 'manage_subjects', 'display_name' => 'Manage Subjects', 'module' => 'subjects'],
            ['name' => 'manage_exams', 'display_name' => 'Manage Exams', 'module' => 'exams'],
            ['name' => 'manage_settings', 'display_name' => 'Manage Settings', 'module' => 'settings'],
            ['name' => 'manage_content', 'display_name' => 'Manage Content', 'module' => 'content'],
            ['name' => 'view_reports', 'display_name' => 'View Reports', 'module' => 'reports'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->insertOrIgnore($permission + ['created_at' => now(), 'updated_at' => now()]);
        }
    }

    private function createSiteSettings()
    {
        $this->command->info('Creating site settings...');
        
        $settings = [
            // General Settings
            ['key' => 'site_name', 'value' => 'EduConnect', 'type' => 'text', 'group' => 'general', 'label' => 'Site Name', 'description' => 'Name of the education platform'],
            ['key' => 'site_tagline', 'value' => 'Connecting Students with Expert Teachers', 'type' => 'text', 'group' => 'general', 'label' => 'Site Tagline', 'description' => 'Tagline displayed on homepage'],
            ['key' => 'site_description', 'value' => 'Find the best teachers and institutes for your learning journey', 'type' => 'textarea', 'group' => 'general', 'label' => 'Site Description', 'description' => 'Main site description'],
            ['key' => 'contact_email', 'value' => 'contact@educonnect.com', 'type' => 'email', 'group' => 'general', 'label' => 'Contact Email', 'description' => 'Primary contact email'],
            ['key' => 'contact_phone', 'value' => '+91 9876543210', 'type' => 'text', 'group' => 'general', 'label' => 'Contact Phone', 'description' => 'Primary contact phone'],
            ['key' => 'address', 'value' => '123 Education Street, Learning City, State 123456', 'type' => 'textarea', 'group' => 'general', 'label' => 'Address', 'description' => 'Physical address'],
            
            // Social Media
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/educonnect', 'type' => 'url', 'group' => 'social', 'label' => 'Facebook URL', 'description' => 'Facebook page URL'],
            ['key' => 'twitter_url', 'value' => 'https://twitter.com/educonnect', 'type' => 'url', 'group' => 'social', 'label' => 'Twitter URL', 'description' => 'Twitter profile URL'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/educonnect', 'type' => 'url', 'group' => 'social', 'label' => 'Instagram URL', 'description' => 'Instagram profile URL'],
            ['key' => 'linkedin_url', 'value' => 'https://linkedin.com/company/educonnect', 'type' => 'url', 'group' => 'social', 'label' => 'LinkedIn URL', 'description' => 'LinkedIn company page'],
            
            // SEO Settings
            ['key' => 'meta_title', 'value' => 'EduConnect - Find Best Teachers & Institutes', 'type' => 'text', 'group' => 'seo', 'label' => 'Meta Title', 'description' => 'Default meta title'],
            ['key' => 'meta_description', 'value' => 'Connect with verified teachers and top institutes. Find personalized tutoring for all subjects and competitive exams.', 'type' => 'textarea', 'group' => 'seo', 'label' => 'Meta Description', 'description' => 'Default meta description'],
            ['key' => 'meta_keywords', 'value' => 'tutors, teachers, institutes, education, learning, online classes', 'type' => 'textarea', 'group' => 'seo', 'label' => 'Meta Keywords', 'description' => 'Default meta keywords'],
            
            // Platform Settings
            ['key' => 'enable_teacher_registration', 'value' => '1', 'type' => 'boolean', 'group' => 'platform', 'label' => 'Enable Teacher Registration', 'description' => 'Allow new teacher registrations'],
            ['key' => 'enable_institute_registration', 'value' => '1', 'type' => 'boolean', 'group' => 'platform', 'label' => 'Enable Institute Registration', 'description' => 'Allow new institute registrations'],
            ['key' => 'require_email_verification', 'value' => '1', 'type' => 'boolean', 'group' => 'platform', 'label' => 'Require Email Verification', 'description' => 'Users must verify email'],
            ['key' => 'auto_approve_teachers', 'value' => '0', 'type' => 'boolean', 'group' => 'platform', 'label' => 'Auto Approve Teachers', 'description' => 'Automatically approve teacher profiles'],
            ['key' => 'auto_approve_institutes', 'value' => '0', 'type' => 'boolean', 'group' => 'platform', 'label' => 'Auto Approve Institutes', 'description' => 'Automatically approve institute profiles'],
        ];

        foreach ($settings as $setting) {
            DB::table('site_settings')->insertOrIgnore($setting + [
                'is_active' => true,
                'sort_order' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    private function createSubjects()
    {
        $this->command->info('Creating subjects...');
        
        $subjects = [
            // Science Subjects
            ['name' => 'Mathematics', 'category' => 'Science', 'grade_level' => 'All', 'icon' => 'fa-calculator'],
            ['name' => 'Physics', 'category' => 'Science', 'grade_level' => 'Class 11-12', 'icon' => 'fa-atom'],
            ['name' => 'Chemistry', 'category' => 'Science', 'grade_level' => 'Class 11-12', 'icon' => 'fa-flask'],
            ['name' => 'Biology', 'category' => 'Science', 'grade_level' => 'Class 11-12', 'icon' => 'fa-dna'],
            ['name' => 'Computer Science', 'category' => 'Science', 'grade_level' => 'Class 11-12', 'icon' => 'fa-laptop-code'],
            
            // Languages
            ['name' => 'English', 'category' => 'Languages', 'grade_level' => 'All', 'icon' => 'fa-language'],
            ['name' => 'Hindi', 'category' => 'Languages', 'grade_level' => 'All', 'icon' => 'fa-language'],
            ['name' => 'Sanskrit', 'category' => 'Languages', 'grade_level' => 'Class 6-12', 'icon' => 'fa-language'],
            ['name' => 'French', 'category' => 'Languages', 'grade_level' => 'Class 6-12', 'icon' => 'fa-language'],
            ['name' => 'German', 'category' => 'Languages', 'grade_level' => 'Class 6-12', 'icon' => 'fa-language'],
            
            // Social Sciences
            ['name' => 'History', 'category' => 'Social Science', 'grade_level' => 'Class 6-12', 'icon' => 'fa-landmark'],
            ['name' => 'Geography', 'category' => 'Social Science', 'grade_level' => 'Class 6-12', 'icon' => 'fa-globe'],
            ['name' => 'Political Science', 'category' => 'Social Science', 'grade_level' => 'Class 11-12', 'icon' => 'fa-balance-scale'],
            ['name' => 'Economics', 'category' => 'Social Science', 'grade_level' => 'Class 11-12', 'icon' => 'fa-chart-line'],
            ['name' => 'Sociology', 'category' => 'Social Science', 'grade_level' => 'Class 11-12', 'icon' => 'fa-users'],
            
            // Commerce
            ['name' => 'Accountancy', 'category' => 'Commerce', 'grade_level' => 'Class 11-12', 'icon' => 'fa-calculator'],
            ['name' => 'Business Studies', 'category' => 'Commerce', 'grade_level' => 'Class 11-12', 'icon' => 'fa-briefcase'],
            
            // Arts
            ['name' => 'Music', 'category' => 'Arts', 'grade_level' => 'All', 'icon' => 'fa-music'],
            ['name' => 'Drawing', 'category' => 'Arts', 'grade_level' => 'All', 'icon' => 'fa-paint-brush'],
            ['name' => 'Dance', 'category' => 'Arts', 'grade_level' => 'All', 'icon' => 'fa-user-ninja'],
            
            // Competitive Exam Subjects
            ['name' => 'Reasoning', 'category' => 'Competitive', 'grade_level' => 'Graduate', 'icon' => 'fa-brain'],
            ['name' => 'General Knowledge', 'category' => 'Competitive', 'grade_level' => 'All', 'icon' => 'fa-book'],
            ['name' => 'Current Affairs', 'category' => 'Competitive', 'grade_level' => 'All', 'icon' => 'fa-newspaper'],
        ];

        foreach ($subjects as $subject) {
            DB::table('subjects')->insertOrIgnore([
                'name' => $subject['name'],
                'slug' => Str::slug($subject['name']),
                'description' => "Learn {$subject['name']} with expert teachers",
                'category' => $subject['category'],
                'grade_level' => $subject['grade_level'],
                'icon' => $subject['icon'],
                'image' => null,
                'sort_order' => 0,
                'is_active' => true,
                'exam_boards' => json_encode(['CBSE', 'ICSE', 'State Board']),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    private function createExamsAndCategories()
    {
        $this->command->info('Creating exam categories and exams...');
        
        // Create Exam Categories
        $categories = [
            ['name' => 'Engineering Entrance', 'slug' => 'engineering-entrance', 'description' => 'Engineering entrance exams like JEE'],
            ['name' => 'Medical Entrance', 'slug' => 'medical-entrance', 'description' => 'Medical entrance exams like NEET'],
            ['name' => 'Management Entrance', 'slug' => 'management-entrance', 'description' => 'Management entrance exams like CAT'],
            ['name' => 'Civil Services', 'slug' => 'civil-services', 'description' => 'Civil services exams like UPSC'],
            ['name' => 'Bank Exams', 'slug' => 'bank-exams', 'description' => 'Banking sector exams'],
            ['name' => 'Board Exams', 'slug' => 'board-exams', 'description' => 'School board examinations'],
        ];

        foreach ($categories as $category) {
            DB::table('exam_categories')->insertOrIgnore($category + [
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Create Exams
        $exams = [
            ['name' => 'JEE Main', 'slug' => 'jee-main', 'category' => 'engineering-entrance', 'type' => 'competitive', 'level' => 'undergraduate', 'conducting_body' => 'NTA'],
            ['name' => 'JEE Advanced', 'slug' => 'jee-advanced', 'category' => 'engineering-entrance', 'type' => 'competitive', 'level' => 'undergraduate', 'conducting_body' => 'IIT'],
            ['name' => 'NEET', 'slug' => 'neet', 'category' => 'medical-entrance', 'type' => 'competitive', 'level' => 'undergraduate', 'conducting_body' => 'NTA'],
            ['name' => 'CAT', 'slug' => 'cat', 'category' => 'management-entrance', 'type' => 'competitive', 'level' => 'postgraduate', 'conducting_body' => 'IIM'],
            ['name' => 'UPSC CSE', 'slug' => 'upsc-cse', 'category' => 'civil-services', 'type' => 'competitive', 'level' => 'postgraduate', 'conducting_body' => 'UPSC'],
            ['name' => 'IBPS PO', 'slug' => 'ibps-po', 'category' => 'bank-exams', 'type' => 'competitive', 'level' => 'postgraduate', 'conducting_body' => 'IBPS'],
            ['name' => 'CBSE Class 10', 'slug' => 'cbse-class-10', 'category' => 'board-exams', 'type' => 'board', 'level' => 'school', 'conducting_body' => 'CBSE'],
            ['name' => 'CBSE Class 12', 'slug' => 'cbse-class-12', 'category' => 'board-exams', 'type' => 'board', 'level' => 'school', 'conducting_body' => 'CBSE'],
        ];

        foreach ($exams as $exam) {
            $category = DB::table('exam_categories')->where('slug', $exam['category'])->first();
            if ($category) {
                DB::table('exams')->insertOrIgnore([
                    'name' => $exam['name'],
                    'slug' => $exam['slug'],
                    'description' => "Preparation for {$exam['name']} exam",
                    'category_id' => $category->id,
                    'type' => $exam['type'],
                    'level' => $exam['level'],
                    'exam_date' => null,
                    'conducting_body' => $exam['conducting_body'],
                    'website' => null,
                    'eligibility' => json_encode(['Graduate', 'Age 18-25']),
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    private function createAdminUser()
    {
        $this->command->info('Creating admin user...');
        
        DB::table('users')->insertOrIgnore([
            'name' => 'Admin User',
            'email' => 'admin@educonnect.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '+91 9876543210',
            'city' => 'Delhi',
            'state' => 'Delhi',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    private function createSampleTeachers()
    {
        $this->command->info('Creating sample teachers...');
        
        $teachers = [
            [
                'name' => 'Dr. Rajesh Kumar',
                'email' => 'rajesh.kumar@example.com',
                'phone' => '+91 9876543211',
                'city' => 'Delhi',
                'subject' => 'Mathematics',
                'qualification' => 'PhD in Mathematics',
                'experience' => 15,
                'bio' => 'Experienced mathematics teacher with 15+ years of teaching experience.',
                'hourly_rate' => 1500,
                'monthly_rate' => 25000,
            ],
            [
                'name' => 'Prof. Priya Sharma',
                'email' => 'priya.sharma@example.com',
                'phone' => '+91 9876543212',
                'city' => 'Mumbai',
                'subject' => 'Physics',
                'qualification' => 'M.Sc Physics, B.Ed',
                'experience' => 12,
                'bio' => 'Physics expert specializing in JEE and NEET preparation.',
                'hourly_rate' => 1200,
                'monthly_rate' => 20000,
            ],
            [
                'name' => 'Mr. Amit Patel',
                'email' => 'amit.patel@example.com',
                'phone' => '+91 9876543213',
                'city' => 'Bangalore',
                'subject' => 'Chemistry',
                'qualification' => 'M.Sc Chemistry',
                'experience' => 10,
                'bio' => 'Chemistry teacher with expertise in organic and inorganic chemistry.',
                'hourly_rate' => 1000,
                'monthly_rate' => 18000,
            ],
            [
                'name' => 'Ms. Sneha Agarwal',
                'email' => 'sneha.agarwal@example.com',
                'phone' => '+91 9876543214',
                'city' => 'Pune',
                'subject' => 'English',
                'qualification' => 'M.A English Literature',
                'experience' => 8,
                'bio' => 'English literature and language expert for all classes.',
                'hourly_rate' => 800,
                'monthly_rate' => 15000,
            ],
            [
                'name' => 'Dr. Suresh Gupta',
                'email' => 'suresh.gupta@example.com',
                'phone' => '+91 9876543215',
                'city' => 'Chennai',
                'subject' => 'Biology',
                'qualification' => 'PhD in Biology, M.D.',
                'experience' => 20,
                'bio' => 'Biology and medical entrance exam specialist.',
                'hourly_rate' => 1800,
                'monthly_rate' => 30000,
            ],
        ];

        foreach ($teachers as $teacherData) {
            $userId = DB::table('users')->insertGetId([
                'name' => $teacherData['name'],
                'email' => $teacherData['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('teacher123'),
                'role' => 'teacher',
                'phone' => $teacherData['phone'],
                'city' => $teacherData['city'],
                'state' => $this->getStateFromCity($teacherData['city']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $subject = DB::table('subjects')->where('name', $teacherData['subject'])->first();
            
            DB::table('teacher_profiles')->insertOrIgnore([
                'user_id' => $userId,
                'subject_id' => $subject ? $subject->id : null,
                'institute_id' => null,
                'branch_id' => null,
                'slug' => Str::slug($teacherData['name']),
                'qualification' => $teacherData['qualification'],
                'qualifications' => $teacherData['qualification'],
                'bio' => $teacherData['bio'],
                'experience_years' => $teacherData['experience'],
                'hourly_rate' => $teacherData['hourly_rate'],
                'monthly_rate' => $teacherData['monthly_rate'],
                'specialization' => $teacherData['subject'] . ' Teacher',
                'languages' => json_encode(['English', 'Hindi']),
                'availability' => 'Monday to Saturday, 9 AM to 9 PM',
                'teaching_mode' => 'both',
                'online_classes' => true,
                'home_tuition' => true,
                'institute_classes' => false,
                'travel_radius_km' => 10,
                'city' => $teacherData['city'],
                'state' => $this->getStateFromCity($teacherData['city']),
                'teaching_city' => $teacherData['city'],
                'rating' => rand(40, 50) / 10,
                'total_students' => rand(50, 200),
                'total_reviews' => rand(20, 100),
                'verified' => true,
                'verification_status' => 'verified',
                'availability_status' => 'available',
                'is_featured' => rand(0, 1),
                'avatar' => null,
                'teaching_preferences' => json_encode(['Interactive Learning', 'Problem Solving', 'Concept Building']),
                'certifications' => json_encode(['B.Ed Certified', 'Subject Expert']),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    private function createSampleInstitutes()
    {
        $this->command->info('Creating sample institutes...');
        
        $institutes = [
            [
                'name' => 'Excellence Academy',
                'email' => 'info@excellenceacademy.com',
                'phone' => '+91 9876543221',
                'city' => 'Delhi',
                'type' => 'coaching',
                'description' => 'Premier coaching institute for JEE and NEET preparation.',
                'established' => 2005,
            ],
            [
                'name' => 'Bright Future Institute',
                'email' => 'contact@brightfuture.com',
                'phone' => '+91 9876543222',
                'city' => 'Mumbai',
                'type' => 'coaching',
                'description' => 'Leading institute for competitive exam preparation.',
                'established' => 2008,
            ],
            [
                'name' => 'Knowledge Hub',
                'email' => 'info@knowledgehub.com',
                'phone' => '+91 9876543223',
                'city' => 'Bangalore',
                'type' => 'coaching',
                'description' => 'Comprehensive coaching for all competitive exams.',
                'established' => 2010,
            ],
            [
                'name' => 'Success Point',
                'email' => 'admin@successpoint.com',
                'phone' => '+91 9876543224',
                'city' => 'Pune',
                'type' => 'coaching',
                'description' => 'Student-focused coaching institute with proven results.',
                'established' => 2012,
            ],
            [
                'name' => 'Elite Coaching Center',
                'email' => 'info@elitecoaching.com',
                'phone' => '+91 9876543225',
                'city' => 'Chennai',
                'type' => 'coaching',
                'description' => 'Elite coaching for medical and engineering entrance exams.',
                'established' => 2007,
            ],
        ];

        foreach ($institutes as $instituteData) {
            $userId = DB::table('users')->insertGetId([
                'name' => $instituteData['name'],
                'email' => $instituteData['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('institute123'),
                'role' => 'institute',
                'phone' => $instituteData['phone'],
                'city' => $instituteData['city'],
                'state' => $this->getStateFromCity($instituteData['city']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::table('institutes')->insertOrIgnore([
                'user_id' => $userId,
                'institute_name' => $instituteData['name'],
                'slug' => Str::slug($instituteData['name']),
                'description' => $instituteData['description'],
                'institute_type' => $instituteData['type'],
                'registration_number' => 'REG' . str_pad($userId, 6, '0', STR_PAD_LEFT),
                'website' => 'https://' . Str::slug($instituteData['name']) . '.com',
                'contact_person' => 'Director',
                'contact_phone' => $instituteData['phone'],
                'contact_email' => $instituteData['email'],
                'address' => 'Main Street, ' . $instituteData['city'],
                'city' => $instituteData['city'],
                'state' => $this->getStateFromCity($instituteData['city']),
                'pincode' => '110001',
                'latitude' => null,
                'longitude' => null,
                'facilities' => json_encode(['Library', 'Computer Lab', 'Study Hall', 'Cafeteria', 'Parking']),
                'working_hours' => json_encode(['Monday-Saturday: 8:00 AM - 8:00 PM', 'Sunday: Closed']),
                'established_year' => $instituteData['established'],
                'total_students' => rand(500, 2000),
                'total_teachers' => rand(20, 100),
                'rating' => rand(40, 50) / 10,
                'total_reviews' => rand(50, 500),
                'verified' => true,
                'verification_status' => 'verified',
                'is_featured' => rand(0, 1),
                'logo' => null,
                'gallery_images' => json_encode(['image1.jpg', 'image2.jpg', 'image3.jpg']),
                'specialization' => 'Competitive Exam Preparation',
                'affiliation' => 'Education Board Certified',
                'parent_institute_id' => null,
                'branch_code' => 'BR001',
                'is_main_branch' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    private function createSampleStudents()
    {
        $this->command->info('Creating sample students...');
        
        $students = [
            [
                'name' => 'Rahul Verma',
                'email' => 'rahul.verma@example.com',
                'phone' => '+91 9876543231',
                'city' => 'Delhi',
                'grade' => 'Class 12',
                'board' => 'CBSE',
            ],
            [
                'name' => 'Priya Singh',
                'email' => 'priya.singh@example.com',
                'phone' => '+91 9876543232',
                'city' => 'Mumbai',
                'grade' => 'Class 11',
                'board' => 'CBSE',
            ],
            [
                'name' => 'Arjun Patel',
                'email' => 'arjun.patel@example.com',
                'phone' => '+91 9876543233',
                'city' => 'Bangalore',
                'grade' => 'Class 10',
                'board' => 'ICSE',
            ],
            [
                'name' => 'Kavya Sharma',
                'email' => 'kavya.sharma@example.com',
                'phone' => '+91 9876543234',
                'city' => 'Pune',
                'grade' => 'Class 12',
                'board' => 'CBSE',
            ],
            [
                'name' => 'Rohan Kumar',
                'email' => 'rohan.kumar@example.com',
                'phone' => '+91 9876543235',
                'city' => 'Chennai',
                'grade' => 'Undergraduate',
                'board' => 'University',
            ],
        ];

        foreach ($students as $studentData) {
            $userId = DB::table('users')->insertGetId([
                'name' => $studentData['name'],
                'email' => $studentData['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('student123'),
                'role' => 'student',
                'phone' => $studentData['phone'],
                'city' => $studentData['city'],
                'state' => $this->getStateFromCity($studentData['city']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::table('student_profiles')->insertOrIgnore([
                'user_id' => $userId,
                'student_id' => 'STU' . str_pad($userId, 4, '0', STR_PAD_LEFT),
                'date_of_birth' => Carbon::now()->subYears(rand(16, 22))->format('Y-m-d'),
                'gender' => collect(['male', 'female'])->random(),
                'grade_level' => $studentData['grade'],
                'school_name' => 'ABC School',
                'board' => $studentData['board'],
                'subjects_of_interest' => json_encode(['Mathematics', 'Physics', 'Chemistry']),
                'learning_goals' => json_encode(['Competitive Exams', 'Board Exams', 'Concept Clarity']),
                'learning_mode' => 'both',
                'parent_name' => 'Parent of ' . $studentData['name'],
                'parent_phone' => '+91 98765432' . rand(10, 99),
                'parent_email' => 'parent.' . strtolower(str_replace(' ', '.', $studentData['name'])) . '@example.com',
                'special_requirements' => 'Regular practice and doubt clearing sessions',
                'budget_min' => 5000,
                'budget_max' => 15000,
                'preferred_timing' => 'Evening 6-8 PM',
                'travel_radius_km' => 5,
                'is_verified' => true,
                'avatar' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    private function createSampleQuestions()
    {
        $this->command->info('Creating sample questions...');
        
        $mathSubject = DB::table('subjects')->where('name', 'Mathematics')->first();
        $jeeExam = DB::table('exams')->where('name', 'JEE Main')->first();
        $adminUser = DB::table('users')->where('email', 'admin@educonnect.com')->first();
        
        if ($mathSubject && $jeeExam && $adminUser) {
            $questions = [
                [
                    'question_text' => 'What is the derivative of sin(x)?',
                    'type' => 'mcq',
                    'options' => json_encode(['cos(x)', '-cos(x)', 'sin(x)', '-sin(x)']),
                    'correct_answers' => json_encode(['cos(x)']),
                    'explanation' => 'The derivative of sin(x) is cos(x)',
                    'difficulty' => 'easy',
                    'class_level' => 'Class 12',
                    'category' => 'practice',
                    'marks' => 1,
                ],
                [
                    'question_text' => 'Find the integral of 2x dx',
                    'type' => 'mcq',
                    'options' => json_encode(['x²', 'x² + C', '2x²', '2x² + C']),
                    'correct_answers' => json_encode(['x² + C']),
                    'explanation' => 'The integral of 2x is x² + C where C is the constant of integration',
                    'difficulty' => 'medium',
                    'class_level' => 'Class 12',
                    'category' => 'practice',
                    'marks' => 2,
                ],
                [
                    'question_text' => 'Solve for x: 2x + 5 = 15',
                    'type' => 'short_answer',
                    'options' => null,
                    'correct_answers' => json_encode(['5', 'x = 5']),
                    'explanation' => '2x = 15 - 5 = 10, therefore x = 5',
                    'difficulty' => 'easy',
                    'class_level' => 'Class 10',
                    'category' => 'practice',
                    'marks' => 1,
                ],
            ];

            foreach ($questions as $question) {
                DB::table('questions')->insertOrIgnore($question + [
                    'subject_id' => $mathSubject->id,
                    'exam_id' => $jeeExam->id,
                    'time_limit' => 120, // 2 minutes
                    'image' => null,
                    'tags' => json_encode(['mathematics', 'algebra', 'calculus']),
                    'status' => 'published',
                    'created_by' => $adminUser->id,
                    'usage_count' => rand(10, 100),
                    'success_rate' => rand(60, 95),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    private function createSampleContent()
    {
        $this->command->info('Creating sample content...');
        
        $adminUser = DB::table('users')->where('email', 'admin@educonnect.com')->first();
        
        if ($adminUser) {
            // Create sample pages
            $pages = [
                [
                    'title' => 'About Us',
                    'slug' => 'about-us',
                    'content' => '<h1>About EduConnect</h1><p>EduConnect is a leading education platform connecting students with expert teachers and institutes.</p>',
                    'excerpt' => 'Learn more about EduConnect and our mission to transform education.',
                    'meta_title' => 'About EduConnect - Leading Education Platform',
                    'meta_description' => 'Learn about EduConnect, our mission, and how we connect students with the best teachers and institutes.',
                    'status' => 'published',
                    'type' => 'about',
                    'show_in_menu' => true,
                ],
                [
                    'title' => 'Privacy Policy',
                    'slug' => 'privacy-policy',
                    'content' => '<h1>Privacy Policy</h1><p>This privacy policy explains how we collect, use, and protect your information.</p>',
                    'excerpt' => 'Our commitment to protecting your privacy and data.',
                    'meta_title' => 'Privacy Policy - EduConnect',
                    'meta_description' => 'Read our privacy policy to understand how we protect your personal information.',
                    'status' => 'published',
                    'type' => 'policy',
                    'show_in_menu' => true,
                ],
                [
                    'title' => 'Terms of Service',
                    'slug' => 'terms-of-service',
                    'content' => '<h1>Terms of Service</h1><p>By using our platform, you agree to these terms and conditions.</p>',
                    'excerpt' => 'Terms and conditions for using EduConnect platform.',
                    'meta_title' => 'Terms of Service - EduConnect',
                    'meta_description' => 'Read our terms of service before using the EduConnect platform.',
                    'status' => 'published',
                    'type' => 'policy',
                    'show_in_menu' => true,
                ],
            ];

            foreach ($pages as $page) {
                DB::table('pages')->insertOrIgnore($page + [
                    'featured_image' => null,
                    'sort_order' => 0,
                    'seo_data' => json_encode(['robots' => 'index,follow']),
                    'author_id' => $adminUser->id,
                    'published_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Create sample blog posts
            $blogPosts = [
                [
                    'title' => 'How to Prepare for JEE Main 2024',
                    'slug' => 'how-to-prepare-jee-main-2024',
                    'content' => '<h1>JEE Main Preparation Guide</h1><p>Comprehensive guide to crack JEE Main 2024 with effective strategies.</p>',
                    'excerpt' => 'Complete preparation strategy for JEE Main 2024 with tips from toppers.',
                    'category' => 'Exam Preparation',
                    'tags' => json_encode(['JEE', 'Engineering', 'Preparation', 'Tips']),
                    'is_featured' => true,
                ],
                [
                    'title' => 'Best Online Learning Platforms in 2024',
                    'slug' => 'best-online-learning-platforms-2024',
                    'content' => '<h1>Top Online Learning Platforms</h1><p>Discover the best online learning platforms for students in 2024.</p>',
                    'excerpt' => 'Review of top online learning platforms and their features.',
                    'category' => 'Education Technology',
                    'tags' => json_encode(['Online Learning', 'EdTech', 'Platforms', 'Students']),
                    'is_featured' => false,
                ],
            ];

            foreach ($blogPosts as $post) {
                DB::table('blog_posts')->insertOrIgnore($post + [
                    'featured_image' => null,
                    'gallery_images' => json_encode(['blog1.jpg', 'blog2.jpg']),
                    'status' => 'published',
                    'meta_title' => $post['title'] . ' - EduConnect Blog',
                    'meta_description' => $post['excerpt'],
                    'views_count' => rand(100, 1000),
                    'likes_count' => rand(10, 100),
                    'comments_count' => rand(5, 50),
                    'allow_comments' => true,
                    'seo_data' => json_encode(['robots' => 'index,follow']),
                    'author_id' => $adminUser->id,
                    'published_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    private function getStateFromCity($city)
    {
        $cityStateMap = [
            'Delhi' => 'Delhi',
            'Mumbai' => 'Maharashtra',
            'Bangalore' => 'Karnataka',
            'Chennai' => 'Tamil Nadu',
            'Pune' => 'Maharashtra',
            'Kolkata' => 'West Bengal',
            'Hyderabad' => 'Telangana',
            'Ahmedabad' => 'Gujarat',
            'Jaipur' => 'Rajasthan',
            'Lucknow' => 'Uttar Pradesh',
        ];

        return $cityStateMap[$city] ?? 'Unknown';
    }
} 