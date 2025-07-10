<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExamCategory;

class ExamCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'National Level Competitive Exams',
                'slug' => 'national-level-competitive-exams',
                'description' => 'National level competitive examinations conducted across India for various government and private sector recruitments.',
                'icon' => 'flag',
                'color' => '#dc3545',
                'sort_order' => 1,
                'status' => 'active',
                'meta_title' => 'National Level Competitive Exams Preparation',
                'meta_description' => 'Prepare for national level competitive exams like UPSC, SSC, Banking, Railway, and more with expert guidance.',
            ],
            [
                'name' => 'State Government Exams',
                'slug' => 'state-government-exams',
                'description' => 'State government examinations for various departments and public sector units at state level.',
                'icon' => 'building',
                'color' => '#28a745',
                'sort_order' => 2,
                'status' => 'active',
                'meta_title' => 'State Government Exams Preparation',
                'meta_description' => 'Expert coaching for state government examinations across all Indian states.',
            ],
            [
                'name' => 'Engineering Entrance Exams',
                'slug' => 'engineering-entrance-exams',
                'description' => 'Engineering entrance examinations for admission to various engineering colleges and universities.',
                'icon' => 'gear',
                'color' => '#007bff',
                'sort_order' => 3,
                'status' => 'active',
                'meta_title' => 'Engineering Entrance Exams Coaching',
                'meta_description' => 'Comprehensive preparation for JEE Main, JEE Advanced, BITSAT, and other engineering entrance exams.',
            ],
            [
                'name' => 'Medical Entrance Exams',
                'slug' => 'medical-entrance-exams',
                'description' => 'Medical entrance examinations for admission to MBBS, BDS, and other medical courses.',
                'icon' => 'heart-pulse',
                'color' => '#fd7e14',
                'sort_order' => 4,
                'status' => 'active',
                'meta_title' => 'Medical Entrance Exams Preparation',
                'meta_description' => 'Expert coaching for NEET, AIIMS, JIPMER, and other medical entrance examinations.',
            ],
            [
                'name' => 'Banking & Finance Exams',
                'slug' => 'banking-finance-exams',
                'description' => 'Banking and financial sector examinations for various positions in banks and financial institutions.',
                'icon' => 'bank',
                'color' => '#6f42c1',
                'sort_order' => 5,
                'status' => 'active',
                'meta_title' => 'Banking & Finance Exams Coaching',
                'meta_description' => 'Prepare for IBPS, SBI, RBI, NABARD, and other banking sector examinations.',
            ],
            [
                'name' => 'Railway Recruitment Exams',
                'slug' => 'railway-recruitment-exams',
                'description' => 'Railway recruitment examinations for various technical and non-technical positions.',
                'icon' => 'train',
                'color' => '#20c997',
                'sort_order' => 6,
                'status' => 'active',
                'meta_title' => 'Railway Recruitment Exams Preparation',
                'meta_description' => 'Comprehensive coaching for RRB NTPC, RRB JE, RRB Group D, and other railway examinations.',
            ],
            [
                'name' => 'Defense & Paramilitary Exams',
                'slug' => 'defense-paramilitary-exams',
                'description' => 'Defense and paramilitary force examinations for officer and soldier positions.',
                'icon' => 'shield',
                'color' => '#198754',
                'sort_order' => 7,
                'status' => 'active',
                'meta_title' => 'Defense & Paramilitary Exams Coaching',
                'meta_description' => 'Expert preparation for NDA, CDS, AFCAT, CAPF, and other defense examinations.',
            ],
            [
                'name' => 'Teaching & Education Exams',
                'slug' => 'teaching-education-exams',
                'description' => 'Teaching eligibility tests and education sector examinations.',
                'icon' => 'graduation-cap',
                'color' => '#e83e8c',
                'sort_order' => 8,
                'status' => 'active',
                'meta_title' => 'Teaching & Education Exams Preparation',
                'meta_description' => 'Prepare for CTET, TET, UGC NET, DSSSB TGT/PGT, and other teaching examinations.',
            ],
            [
                'name' => 'Management Entrance Exams',
                'slug' => 'management-entrance-exams',
                'description' => 'Management entrance examinations for MBA and other management programs.',
                'icon' => 'briefcase',
                'color' => '#ffc107',
                'sort_order' => 9,
                'status' => 'active',
                'meta_title' => 'Management Entrance Exams Coaching',
                'meta_description' => 'Expert guidance for CAT, XAT, GMAT, MAT, CMAT, and other management entrance exams.',
            ],
            [
                'name' => 'Law Entrance Exams',
                'slug' => 'law-entrance-exams',
                'description' => 'Law entrance examinations for admission to law colleges and universities.',
                'icon' => 'scale-balanced',
                'color' => '#6c757d',
                'sort_order' => 10,
                'status' => 'active',
                'meta_title' => 'Law Entrance Exams Preparation',
                'meta_description' => 'Comprehensive coaching for CLAT, AILET, LSAT, and other law entrance examinations.',
            ],
            [
                'name' => 'CA, CS & Professional Exams',
                'slug' => 'ca-cs-professional-exams',
                'description' => 'Chartered Accountancy, Company Secretary, and other professional course examinations.',
                'icon' => 'calculator',
                'color' => '#17a2b8',
                'sort_order' => 11,
                'status' => 'active',
                'meta_title' => 'CA, CS & Professional Exams Coaching',
                'meta_description' => 'Expert coaching for CA, CS, CMA, ACCA, and other professional examinations.',
            ],
            [
                'name' => 'School Board Exams',
                'slug' => 'school-board-exams',
                'description' => 'School board examinations for classes 10th and 12th across different boards.',
                'icon' => 'school',
                'color' => '#fd7e14',
                'sort_order' => 12,
                'status' => 'active',
                'meta_title' => 'School Board Exams Preparation',
                'meta_description' => 'Comprehensive preparation for CBSE, ICSE, State Board, and other school examinations.',
            ],
            [
                'name' => 'University Entrance Exams',
                'slug' => 'university-entrance-exams',
                'description' => 'University-specific entrance examinations for various undergraduate and postgraduate courses.',
                'icon' => 'university',
                'color' => '#6610f2',
                'sort_order' => 13,
                'status' => 'active',
                'meta_title' => 'University Entrance Exams Coaching',
                'meta_description' => 'Prepare for BHU, JNU, DU, AMU, and other university entrance examinations.',
            ],
            [
                'name' => 'Computer & IT Exams',
                'slug' => 'computer-it-exams',
                'description' => 'Computer science and information technology related competitive examinations.',
                'icon' => 'computer',
                'color' => '#0dcaf0',
                'sort_order' => 14,
                'status' => 'active',
                'meta_title' => 'Computer & IT Exams Preparation',
                'meta_description' => 'Expert coaching for GATE CS, NIELIT, programming contests, and IT sector examinations.',
            ],
            [
                'name' => 'Research & Fellowship Exams',
                'slug' => 'research-fellowship-exams',
                'description' => 'Research and fellowship examinations for higher studies and academic careers.',
                'icon' => 'microscope',
                'color' => '#dc3545',
                'sort_order' => 15,
                'status' => 'active',
                'meta_title' => 'Research & Fellowship Exams Coaching',
                'meta_description' => 'Prepare for UGC NET, CSIR NET, JRF, GATE, and other research fellowship examinations.',
            ],
        ];

        foreach ($categories as $category) {
            ExamCategory::create($category);
        }
    }
}
