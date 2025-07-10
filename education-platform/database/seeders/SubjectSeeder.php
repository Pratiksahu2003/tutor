<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;
use Illuminate\Support\Str;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            // Mathematics
            [
                'name' => 'Mathematics',
                'short_name' => 'Math',
                'description' => 'Basic to advanced mathematics including algebra, geometry, calculus',
                'category' => 'mathematics',
                'level' => 'secondary',
                'color' => '#FF6B35',
                'sort_order' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'Advanced Mathematics',
                'short_name' => 'Adv Math',
                'description' => 'Advanced topics in mathematics for higher grades',
                'category' => 'mathematics',
                'level' => 'higher_secondary',
                'color' => '#FF8E53',
                'sort_order' => 2,
                'status' => 'active',
            ],
            [
                'name' => 'Quantitative Aptitude',
                'short_name' => 'Quant',
                'description' => 'Numerical ability and quantitative reasoning for competitive exams',
                'category' => 'quantitative_aptitude',
                'level' => 'competitive',
                'color' => '#F7931E',
                'sort_order' => 3,
                'status' => 'active',
            ],

            // Sciences
            [
                'name' => 'Physics',
                'short_name' => 'Phy',
                'description' => 'Classical and modern physics concepts',
                'category' => 'science',
                'level' => 'higher_secondary',
                'color' => '#4ECDC4',
                'sort_order' => 4,
                'status' => 'active',
            ],
            [
                'name' => 'Chemistry',
                'short_name' => 'Chem',
                'description' => 'Organic, inorganic, and physical chemistry',
                'category' => 'science',
                'level' => 'higher_secondary',
                'color' => '#45B7D1',
                'sort_order' => 5,
                'status' => 'active',
            ],
            [
                'name' => 'Biology',
                'short_name' => 'Bio',
                'description' => 'Life sciences including botany, zoology, and human biology',
                'category' => 'science',
                'level' => 'higher_secondary',
                'color' => '#96CEB4',
                'sort_order' => 6,
                'status' => 'active',
            ],

            // Languages
            [
                'name' => 'English',
                'short_name' => 'Eng',
                'description' => 'English language, literature, and communication skills',
                'category' => 'language',
                'level' => 'secondary',
                'color' => '#FFEAA7',
                'sort_order' => 7,
                'status' => 'active',
            ],
            [
                'name' => 'Hindi',
                'short_name' => 'Hindi',
                'description' => 'Hindi language and literature',
                'category' => 'language',
                'level' => 'secondary',
                'color' => '#DDA0DD',
                'sort_order' => 8,
                'status' => 'active',
            ],

            // Social Sciences
            [
                'name' => 'History',
                'short_name' => 'Hist',
                'description' => 'World history, Indian history, and historical analysis',
                'category' => 'social_science',
                'level' => 'secondary',
                'color' => '#FDCB6E',
                'sort_order' => 9,
                'status' => 'active',
            ],
            [
                'name' => 'Geography',
                'short_name' => 'Geo',
                'description' => 'Physical and human geography',
                'category' => 'social_science',
                'level' => 'secondary',
                'color' => '#6C5CE7',
                'sort_order' => 10,
                'status' => 'active',
            ],
            [
                'name' => 'Political Science',
                'short_name' => 'Pol Sci',
                'description' => 'Government, politics, and civic studies',
                'category' => 'social_science',
                'level' => 'higher_secondary',
                'color' => '#A29BFE',
                'sort_order' => 11,
                'status' => 'active',
            ],
            [
                'name' => 'Economics',
                'short_name' => 'Econ',
                'description' => 'Micro and macroeconomics principles',
                'category' => 'social_science',
                'level' => 'higher_secondary',
                'color' => '#FD79A8',
                'sort_order' => 12,
                'status' => 'active',
            ],

            // Computer Science
            [
                'name' => 'Computer Science',
                'short_name' => 'CS',
                'description' => 'Programming, algorithms, and computer systems',
                'category' => 'computer_science',
                'level' => 'higher_secondary',
                'color' => '#0984E3',
                'sort_order' => 13,
                'status' => 'active',
            ],
            [
                'name' => 'Programming',
                'short_name' => 'Prog',
                'description' => 'Software development and coding',
                'category' => 'computer_science',
                'level' => 'undergraduate',
                'color' => '#00B894',
                'sort_order' => 14,
                'status' => 'active',
            ],

            // Engineering
            [
                'name' => 'Engineering Mathematics',
                'short_name' => 'Eng Math',
                'description' => 'Mathematics for engineering students',
                'category' => 'engineering',
                'level' => 'undergraduate',
                'color' => '#E17055',
                'sort_order' => 15,
                'status' => 'active',
            ],
            [
                'name' => 'Mechanical Engineering',
                'short_name' => 'Mech',
                'description' => 'Mechanical engineering principles and applications',
                'category' => 'engineering',
                'level' => 'undergraduate',
                'color' => '#636E72',
                'sort_order' => 16,
                'status' => 'active',
            ],

            // Medical
            [
                'name' => 'Anatomy',
                'short_name' => 'Anat',
                'description' => 'Human anatomy and physiology',
                'category' => 'medical',
                'level' => 'undergraduate',
                'color' => '#E84393',
                'sort_order' => 17,
                'status' => 'active',
            ],
            [
                'name' => 'Pharmacology',
                'short_name' => 'Pharma',
                'description' => 'Study of drugs and their effects',
                'category' => 'medical',
                'level' => 'undergraduate',
                'color' => '#9B59B6',
                'sort_order' => 18,
                'status' => 'active',
            ],

            // Commerce
            [
                'name' => 'Accounting',
                'short_name' => 'Acc',
                'description' => 'Financial accounting and bookkeeping',
                'category' => 'commerce',
                'level' => 'higher_secondary',
                'color' => '#00CEC9',
                'sort_order' => 19,
                'status' => 'active',
            ],
            [
                'name' => 'Business Studies',
                'short_name' => 'Bus',
                'description' => 'Business principles, management, and entrepreneurship',
                'category' => 'commerce',
                'level' => 'higher_secondary',
                'color' => '#55A3FF',
                'sort_order' => 20,
                'status' => 'active',
            ],

            // Arts
            [
                'name' => 'Fine Arts',
                'short_name' => 'Arts',
                'description' => 'Visual arts, design principles, and creativity',
                'category' => 'arts',
                'level' => 'secondary',
                'color' => '#FF7675',
                'sort_order' => 21,
                'status' => 'active',
            ],
            [
                'name' => 'Music',
                'short_name' => 'Music',
                'description' => 'Music theory, instruments, and performance',
                'category' => 'arts',
                'level' => 'secondary',
                'color' => '#FDCB6E',
                'sort_order' => 22,
                'status' => 'active',
            ],

            // Law
            [
                'name' => 'Constitutional Law',
                'short_name' => 'Law',
                'description' => 'Constitutional and legal principles',
                'category' => 'law',
                'level' => 'undergraduate',
                'color' => '#2D3436',
                'sort_order' => 23,
                'status' => 'active',
            ],

            // Management
            [
                'name' => 'Management Studies',
                'short_name' => 'Mgmt',
                'description' => 'Management principles and business administration',
                'category' => 'management',
                'level' => 'postgraduate',
                'color' => '#6C5CE7',
                'sort_order' => 24,
                'status' => 'active',
            ],

            // Competitive Exam Specific
            [
                'name' => 'General Knowledge',
                'short_name' => 'GK',
                'description' => 'General knowledge and current affairs',
                'category' => 'general_knowledge',
                'level' => 'competitive',
                'color' => '#FDCB6E',
                'sort_order' => 25,
                'status' => 'active',
            ],
            [
                'name' => 'Current Affairs',
                'short_name' => 'CA',
                'description' => 'Latest news and current events',
                'category' => 'current_affairs',
                'level' => 'competitive',
                'color' => '#E17055',
                'sort_order' => 26,
                'status' => 'active',
            ],
            [
                'name' => 'Reasoning',
                'short_name' => 'Reason',
                'description' => 'Logical and analytical reasoning',
                'category' => 'reasoning',
                'level' => 'competitive',
                'color' => '#00B894',
                'sort_order' => 27,
                'status' => 'active',
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::create([
                'name' => $subject['name'],
                'slug' => Str::slug($subject['name']),
                'short_name' => $subject['short_name'],
                'description' => $subject['description'],
                'category' => $subject['category'],
                'level' => $subject['level'],
                'color' => $subject['color'],
                'sort_order' => $subject['sort_order'],
                'status' => $subject['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
