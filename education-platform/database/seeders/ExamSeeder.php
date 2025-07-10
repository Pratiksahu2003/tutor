<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\ExamCategory;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get category IDs
        $categories = ExamCategory::pluck('id', 'slug')->toArray();

        $exams = [
            // National Level Competitive Exams
            [
                'name' => 'Union Public Service Commission - Civil Services Examination',
                'slug' => 'upsc-civil-services',
                'short_name' => 'UPSC CSE',
                'description' => 'The most prestigious examination in India for recruitment to various civil services.',
                'exam_category_id' => $categories['national-level-competitive-exams'],
                'conducting_body' => 'Union Public Service Commission',
                'exam_type' => 'national',
                'frequency' => 'yearly',
                'eligibility' => 'Graduate from recognized university, age 21-32 years',
                'exam_pattern' => 'Prelims (Objective) + Mains (Descriptive) + Interview',
                'official_website' => 'https://upsc.gov.in',
                'total_marks' => 2025,
                'duration_minutes' => 720,
                'featured' => true,
                'sort_order' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'Staff Selection Commission - Combined Graduate Level',
                'slug' => 'ssc-cgl',
                'short_name' => 'SSC CGL',
                'description' => 'Combined Graduate Level examination for Group B and C posts in central government.',
                'exam_category_id' => $categories['national-level-competitive-exams'],
                'conducting_body' => 'Staff Selection Commission',
                'exam_type' => 'national',
                'frequency' => 'yearly',
                'eligibility' => 'Graduate from recognized university',
                'exam_pattern' => 'Tier 1 + Tier 2 + Tier 3 + Tier 4',
                'official_website' => 'https://ssc.nic.in',
                'total_marks' => 200,
                'duration_minutes' => 60,
                'featured' => true,
                'sort_order' => 2,
                'status' => 'active',
            ],

            // Engineering Entrance Exams
            [
                'name' => 'Joint Entrance Examination Main',
                'slug' => 'jee-main',
                'short_name' => 'JEE Main',
                'description' => 'National level entrance examination for admission to NITs, IIITs, and CFTIs.',
                'exam_category_id' => $categories['engineering-entrance-exams'],
                'conducting_body' => 'National Testing Agency',
                'exam_type' => 'national',
                'frequency' => 'twice_yearly',
                'eligibility' => '12th pass with PCM, minimum 75% marks',
                'exam_pattern' => 'Multiple Choice Questions (MCQ)',
                'official_website' => 'https://jeemain.nta.nic.in',
                'total_marks' => 300,
                'duration_minutes' => 180,
                'featured' => true,
                'sort_order' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'Joint Entrance Examination Advanced',
                'slug' => 'jee-advanced',
                'short_name' => 'JEE Advanced',
                'description' => 'Entrance examination for admission to Indian Institutes of Technology (IITs).',
                'exam_category_id' => $categories['engineering-entrance-exams'],
                'conducting_body' => 'IIT (Rotating)',
                'exam_type' => 'national',
                'frequency' => 'yearly',
                'eligibility' => 'Qualified JEE Main with top ranks',
                'exam_pattern' => 'Two papers with MCQ and numerical type questions',
                'official_website' => 'https://jeeadv.ac.in',
                'total_marks' => 372,
                'duration_minutes' => 360,
                'featured' => true,
                'sort_order' => 2,
                'status' => 'active',
            ],

            // Medical Entrance Exams
            [
                'name' => 'National Eligibility cum Entrance Test',
                'slug' => 'neet-ug',
                'short_name' => 'NEET UG',
                'description' => 'National level entrance examination for MBBS and BDS admission.',
                'exam_category_id' => $categories['medical-entrance-exams'],
                'conducting_body' => 'National Testing Agency',
                'exam_type' => 'national',
                'frequency' => 'yearly',
                'eligibility' => '12th pass with PCB, minimum 50% marks',
                'exam_pattern' => 'Multiple Choice Questions (MCQ)',
                'official_website' => 'https://neet.nta.nic.in',
                'total_marks' => 720,
                'duration_minutes' => 200,
                'featured' => true,
                'sort_order' => 1,
                'status' => 'active',
            ],

            // Banking & Finance Exams
            [
                'name' => 'Institute of Banking Personnel Selection - PO',
                'slug' => 'ibps-po',
                'short_name' => 'IBPS PO',
                'description' => 'Common recruitment process for Probationary Officers in public sector banks.',
                'exam_category_id' => $categories['banking-finance-exams'],
                'conducting_body' => 'Institute of Banking Personnel Selection',
                'exam_type' => 'national',
                'frequency' => 'yearly',
                'eligibility' => 'Graduate from recognized university',
                'exam_pattern' => 'Prelims + Mains + Interview',
                'official_website' => 'https://ibps.in',
                'total_marks' => 200,
                'duration_minutes' => 120,
                'featured' => true,
                'sort_order' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'State Bank of India - PO',
                'slug' => 'sbi-po',
                'short_name' => 'SBI PO',
                'description' => 'Recruitment examination for Probationary Officers in State Bank of India.',
                'exam_category_id' => $categories['banking-finance-exams'],
                'conducting_body' => 'State Bank of India',
                'exam_type' => 'national',
                'frequency' => 'yearly',
                'eligibility' => 'Graduate from recognized university',
                'exam_pattern' => 'Prelims + Mains + Interview',
                'official_website' => 'https://sbi.co.in/careers',
                'total_marks' => 200,
                'duration_minutes' => 120,
                'featured' => true,
                'sort_order' => 2,
                'status' => 'active',
            ],

            // Railway Recruitment Exams
            [
                'name' => 'Railway Recruitment Board - NTPC',
                'slug' => 'rrb-ntpc',
                'short_name' => 'RRB NTPC',
                'description' => 'Non-Technical Popular Categories recruitment in Indian Railways.',
                'exam_category_id' => $categories['railway-recruitment-exams'],
                'conducting_body' => 'Railway Recruitment Board',
                'exam_type' => 'national',
                'frequency' => 'yearly',
                'eligibility' => 'Graduation for higher posts, 12th for others',
                'exam_pattern' => 'CBT 1 + CBT 2 + Typing/Computer Test',
                'official_website' => 'https://rrbcdg.gov.in',
                'total_marks' => 120,
                'duration_minutes' => 90,
                'featured' => true,
                'sort_order' => 1,
                'status' => 'active',
            ],

            // Defense & Paramilitary Exams
            [
                'name' => 'National Defence Academy',
                'slug' => 'nda',
                'short_name' => 'NDA',
                'description' => 'Joint services entrance examination for Army, Navy, and Air Force.',
                'exam_category_id' => $categories['defense-paramilitary-exams'],
                'conducting_body' => 'Union Public Service Commission',
                'exam_type' => 'national',
                'frequency' => 'twice_yearly',
                'eligibility' => '12th pass, unmarried male candidates',
                'exam_pattern' => 'Written Exam + SSB Interview',
                'official_website' => 'https://upsc.gov.in',
                'total_marks' => 900,
                'duration_minutes' => 450,
                'featured' => true,
                'sort_order' => 1,
                'status' => 'active',
            ],

            // Teaching & Education Exams
            [
                'name' => 'Central Teacher Eligibility Test',
                'slug' => 'ctet',
                'short_name' => 'CTET',
                'description' => 'Central teacher eligibility test for primary and upper primary teachers.',
                'exam_category_id' => $categories['teaching-education-exams'],
                'conducting_body' => 'CBSE',
                'exam_type' => 'national',
                'frequency' => 'yearly',
                'eligibility' => 'Diploma/Graduation in Education',
                'exam_pattern' => 'Paper 1 (Classes I-V) and/or Paper 2 (Classes VI-VIII)',
                'official_website' => 'https://ctet.nic.in',
                'total_marks' => 150,
                'duration_minutes' => 150,
                'featured' => true,
                'sort_order' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'University Grants Commission - National Eligibility Test',
                'slug' => 'ugc-net',
                'short_name' => 'UGC NET',
                'description' => 'National eligibility test for determining eligibility for lectureship and JRF.',
                'exam_category_id' => $categories['teaching-education-exams'],
                'conducting_body' => 'National Testing Agency',
                'exam_type' => 'national',
                'frequency' => 'twice_yearly',
                'eligibility' => 'Post Graduation with 55% marks',
                'exam_pattern' => 'Paper 1 + Paper 2',
                'official_website' => 'https://ugcnet.nta.nic.in',
                'total_marks' => 300,
                'duration_minutes' => 180,
                'featured' => true,
                'sort_order' => 2,
                'status' => 'active',
            ],

            // Management Entrance Exams
            [
                'name' => 'Common Admission Test',
                'slug' => 'cat',
                'short_name' => 'CAT',
                'description' => 'Computer-based test for admission to Indian Institutes of Management (IIMs).',
                'exam_category_id' => $categories['management-entrance-exams'],
                'conducting_body' => 'IIM (Rotating)',
                'exam_type' => 'national',
                'frequency' => 'yearly',
                'eligibility' => 'Bachelor\'s degree with 50% marks',
                'exam_pattern' => 'Quantitative Ability + Verbal Ability + Data Interpretation',
                'official_website' => 'https://iimcat.ac.in',
                'total_marks' => 300,
                'duration_minutes' => 180,
                'featured' => true,
                'sort_order' => 1,
                'status' => 'active',
            ],

            // Law Entrance Exams
            [
                'name' => 'Common Law Admission Test',
                'slug' => 'clat',
                'short_name' => 'CLAT',
                'description' => 'National level entrance examination for admission to National Law Universities.',
                'exam_category_id' => $categories['law-entrance-exams'],
                'conducting_body' => 'Consortium of NLUs',
                'exam_type' => 'national',
                'frequency' => 'yearly',
                'eligibility' => '12th pass for UG, LLB for PG',
                'exam_pattern' => 'Multiple Choice Questions',
                'official_website' => 'https://consortiumofnlus.ac.in',
                'total_marks' => 200,
                'duration_minutes' => 120,
                'featured' => true,
                'sort_order' => 1,
                'status' => 'active',
            ],

            // CA, CS & Professional Exams
            [
                'name' => 'Chartered Accountant - Foundation',
                'slug' => 'ca-foundation',
                'short_name' => 'CA Foundation',
                'description' => 'Entry level examination for Chartered Accountancy course.',
                'exam_category_id' => $categories['ca-cs-professional-exams'],
                'conducting_body' => 'Institute of Chartered Accountants of India',
                'exam_type' => 'national',
                'frequency' => 'twice_yearly',
                'eligibility' => '12th pass from recognized board',
                'exam_pattern' => 'Objective + Subjective',
                'official_website' => 'https://icai.org',
                'total_marks' => 400,
                'duration_minutes' => 360,
                'featured' => true,
                'sort_order' => 1,
                'status' => 'active',
            ],

            // Computer & IT Exams
            [
                'name' => 'Graduate Aptitude Test in Engineering',
                'slug' => 'gate',
                'short_name' => 'GATE',
                'description' => 'National level examination for admission to postgraduate programs in engineering.',
                'exam_category_id' => $categories['computer-it-exams'],
                'conducting_body' => 'IIT/IISc (Rotating)',
                'exam_type' => 'national',
                'frequency' => 'yearly',
                'eligibility' => 'Bachelor\'s degree in Engineering/Technology',
                'exam_pattern' => 'Computer Based Test',
                'official_website' => 'https://gate.iitd.ac.in',
                'total_marks' => 100,
                'duration_minutes' => 180,
                'featured' => true,
                'sort_order' => 1,
                'status' => 'active',
            ],
        ];

        foreach ($exams as $exam) {
            Exam::create($exam);
        }
    }
}
