<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Subject;
use App\Models\User;

class FaqQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user and subjects
        $admin = User::where('role', 'admin')->first();
        $subjects = Subject::all();
        
        if (!$admin || $subjects->isEmpty()) {
            $this->command->info('Please ensure admin user and subjects exist before running this seeder.');
            return;
        }

        $mathSubject = $subjects->where('name', 'like', '%Math%')->first() ?? $subjects->first();
        $scienceSubject = $subjects->where('name', 'like', '%Physics%')->first() ?? 
                         $subjects->where('name', 'like', '%Science%')->first() ?? $mathSubject;
        $englishSubject = $subjects->where('name', 'like', '%English%')->first() ?? $mathSubject;

        // General Platform FAQ Questions
        Question::create([
            'title' => 'How do I register on the education platform?',
            'question_text' => 'What are the steps to create an account and start learning?',
            'type' => 'long_answer',
            'difficulty' => 'easy',
            'marks' => 1,
            'explanation' => 'To register on our platform: 1) Click the "Sign Up" button, 2) Choose your role (Student, Teacher, or Institute), 3) Fill in your details, 4) Verify your email address, 5) Complete your profile setup. You can then start browsing teachers or institutes immediately.',
            'hint' => 'Registration is free and takes less than 5 minutes',
            'subject_id' => $mathSubject->id,
            'topic' => 'Platform Usage',
            'category' => 'quiz',
            'tags' => ['faq', 'registration', 'platform', 'getting-started'],
            'status' => 'published',
            'created_by' => $admin->id,
            'is_public' => true,
        ]);

        Question::create([
            'title' => 'How do I find the right teacher for my subject?',
            'question_text' => 'What features can I use to search and filter teachers based on my requirements?',
            'type' => 'long_answer',
            'difficulty' => 'easy',
            'marks' => 2,
            'explanation' => 'You can find teachers using our advanced search filters: 1) Select your subject from the dropdown, 2) Choose your preferred location or opt for online classes, 3) Filter by experience level, 4) Set your budget range, 5) Check teacher ratings and reviews, 6) View their teaching schedule and availability.',
            'hint' => 'Use multiple filters to narrow down your search results',
            'subject_id' => $mathSubject->id,
            'topic' => 'Teacher Search',
            'category' => 'quiz',
            'tags' => ['faq', 'teacher-search', 'filters', 'finding-teachers'],
            'status' => 'published',
            'created_by' => $admin->id,
            'is_public' => true,
        ]);

        Question::create([
            'title' => 'Is online learning as effective as offline learning?',
            'question_text' => 'What are the benefits and considerations of online vs offline tutoring?',
            'type' => 'long_answer',
            'difficulty' => 'medium',
            'marks' => 3,
            'explanation' => 'Online learning can be equally effective when: 1) You have a stable internet connection, 2) The teacher uses interactive tools and methods, 3) Regular practice sessions are conducted, 4) Doubt-clearing sessions are available. Benefits include flexibility, recorded sessions, and access to teachers from anywhere. However, some subjects requiring hands-on practice may benefit from offline learning.',
            'hint' => 'Effectiveness depends on teaching methods and student engagement',
            'subject_id' => $scienceSubject->id,
            'topic' => 'Learning Methods',
            'category' => 'quiz',
            'tags' => ['faq', 'online-learning', 'offline-learning', 'effectiveness'],
            'status' => 'published',
            'created_by' => $admin->id,
            'is_public' => true,
        ]);

        Question::create([
            'title' => 'How do I book a trial class with a teacher?',
            'question_text' => 'What is the process for scheduling and attending a trial session?',
            'type' => 'short_answer',
            'difficulty' => 'easy',
            'marks' => 2,
            'explanation' => 'To book a trial class: 1) Go to the teacher\'s profile, 2) Click "Book Trial Class", 3) Select available time slots, 4) Provide your learning objectives, 5) Confirm booking, 6) Receive meeting details via email/SMS. Most teachers offer 30-60 minute trial sessions at discounted rates.',
            'hint' => 'Trial classes help you evaluate teaching style before committing',
            'subject_id' => $mathSubject->id,
            'topic' => 'Booking Process',
            'category' => 'quiz',
            'tags' => ['faq', 'trial-class', 'booking', 'scheduling'],
            'status' => 'published',
            'created_by' => $admin->id,
            'is_public' => true,
        ]);

        // Subject-specific FAQ Questions

        // Mathematics FAQ
        Question::create([
            'title' => 'How to improve problem-solving skills in Mathematics?',
            'question_text' => 'What are effective strategies for developing mathematical problem-solving abilities?',
            'type' => 'long_answer',
            'difficulty' => 'medium',
            'marks' => 4,
            'explanation' => 'To improve mathematical problem-solving: 1) Practice regularly with diverse problem types, 2) Understand concepts before memorizing formulas, 3) Break complex problems into smaller steps, 4) Learn from mistakes by reviewing incorrect solutions, 5) Practice mental math to improve calculation speed, 6) Use visual aids like graphs and diagrams, 7) Solve previous year question papers.',
            'hint' => 'Consistent practice and conceptual understanding are key',
            'subject_id' => $mathSubject->id,
            'topic' => 'Problem Solving',
            'chapter' => 'General Mathematics',
            'class_level' => '10',
            'board' => 'CBSE',
            'category' => 'quiz',
            'tags' => ['faq', 'mathematics', 'problem-solving', 'study-tips'],
            'status' => 'published',
            'created_by' => $admin->id,
            'is_public' => true,
        ]);

        Question::create([
            'title' => 'What are the most important math topics for competitive exams?',
            'question_text' => 'Which mathematical concepts should I focus on for entrance examinations?',
            'type' => 'long_answer',
            'difficulty' => 'medium',
            'marks' => 3,
            'explanation' => 'Key math topics for competitive exams: 1) Algebra - equations, inequalities, progressions, 2) Geometry - triangles, circles, coordinate geometry, 3) Trigonometry - ratios, identities, applications, 4) Calculus - derivatives, integrals, limits, 5) Statistics and Probability, 6) Number theory and combinatorics. Focus on fundamentals first, then advance to complex problems.',
            'hint' => 'Build strong foundation in basics before attempting advanced topics',
            'subject_id' => $mathSubject->id,
            'topic' => 'Competitive Exams',
            'chapter' => 'Exam Preparation',
            'class_level' => '12',
            'board' => 'CBSE',
            'category' => 'quiz',
            'tags' => ['faq', 'mathematics', 'competitive-exams', 'jee', 'neet'],
            'status' => 'published',
            'created_by' => $admin->id,
            'is_public' => true,
        ]);

        // Science FAQ
        Question::create([
            'title' => 'How to remember chemical formulas effectively?',
            'question_text' => 'What techniques can help in memorizing and understanding chemical formulas?',
            'type' => 'long_answer',
            'difficulty' => 'medium',
            'marks' => 3,
            'explanation' => 'Effective methods to remember chemical formulas: 1) Understand valency concepts and ionic charges, 2) Practice writing formulas by balancing charges, 3) Group similar compounds together, 4) Use mnemonics for common formulas, 5) Create flashcards for regular revision, 6) Practice naming compounds from formulas and vice versa, 7) Connect formulas to their real-world applications.',
            'hint' => 'Understanding the logic behind formulas makes memorization easier',
            'subject_id' => $scienceSubject->id,
            'topic' => 'Chemical Formulas',
            'chapter' => 'Chemistry Basics',
            'class_level' => '9',
            'board' => 'CBSE',
            'category' => 'quiz',
            'tags' => ['faq', 'chemistry', 'formulas', 'memorization', 'study-tips'],
            'status' => 'published',
            'created_by' => $admin->id,
            'is_public' => true,
        ]);

        Question::create([
            'title' => 'What is the best way to prepare for physics numericals?',
            'question_text' => 'How should I approach solving physics numerical problems effectively?',
            'type' => 'long_answer',
            'difficulty' => 'medium',
            'marks' => 4,
            'explanation' => 'Strategy for physics numericals: 1) Master the fundamental concepts and formulas, 2) Understand the derivation of formulas, 3) Practice unit conversions regularly, 4) Start with simple problems before complex ones, 5) Draw diagrams when applicable, 6) List given data and required answers clearly, 7) Show all calculation steps, 8) Check units in final answers, 9) Practice previous year questions.',
            'hint' => 'Conceptual clarity is more important than formula memorization',
            'subject_id' => $scienceSubject->id,
            'topic' => 'Numerical Problems',
            'chapter' => 'Physics Problem Solving',
            'class_level' => '11',
            'board' => 'CBSE',
            'category' => 'quiz',
            'tags' => ['faq', 'physics', 'numericals', 'problem-solving', 'calculations'],
            'status' => 'published',
            'created_by' => $admin->id,
            'is_public' => true,
        ]);

        // English FAQ
        Question::create([
            'title' => 'How to improve English vocabulary effectively?',
            'question_text' => 'What are the best methods to expand English vocabulary for better communication?',
            'type' => 'long_answer',
            'difficulty' => 'easy',
            'marks' => 3,
            'explanation' => 'Effective vocabulary building techniques: 1) Read diverse materials daily (newspapers, books, articles), 2) Maintain a vocabulary journal with new words, 3) Learn word roots, prefixes, and suffixes, 4) Use new words in sentences and conversations, 5) Play word games and solve crosswords, 6) Use vocabulary apps for daily practice, 7) Learn synonyms and antonyms, 8) Practice with flashcards for revision.',
            'hint' => 'Consistent daily practice is more effective than intensive cramming',
            'subject_id' => $englishSubject->id,
            'topic' => 'Vocabulary Building',
            'chapter' => 'Language Skills',
            'class_level' => '8',
            'board' => 'CBSE',
            'category' => 'quiz',
            'tags' => ['faq', 'english', 'vocabulary', 'language-skills', 'communication'],
            'status' => 'published',
            'created_by' => $admin->id,
            'is_public' => true,
        ]);

        Question::create([
            'title' => 'How to write better essays and compositions?',
            'question_text' => 'What techniques can improve my essay writing and composition skills?',
            'type' => 'long_answer',
            'difficulty' => 'medium',
            'marks' => 4,
            'explanation' => 'Essay writing improvement tips: 1) Plan your essay with an outline before writing, 2) Start with an engaging introduction, 3) Develop clear topic sentences for each paragraph, 4) Use varied sentence structures and vocabulary, 5) Support arguments with examples and evidence, 6) Maintain logical flow between paragraphs, 7) Write a strong conclusion, 8) Proofread for grammar and spelling errors, 9) Practice different essay types regularly.',
            'hint' => 'Good essays require planning, clear structure, and multiple revisions',
            'subject_id' => $englishSubject->id,
            'topic' => 'Essay Writing',
            'chapter' => 'Composition Skills',
            'class_level' => '10',
            'board' => 'CBSE',
            'category' => 'quiz',
            'tags' => ['faq', 'english', 'essay-writing', 'composition', 'writing-skills'],
            'status' => 'published',
            'created_by' => $admin->id,
            'is_public' => true,
        ]);

        // Platform-specific FAQ
        Question::create([
            'title' => 'What payment methods are accepted on the platform?',
            'question_text' => 'Which payment options can I use to pay for classes and courses?',
            'type' => 'short_answer',
            'difficulty' => 'easy',
            'marks' => 1,
            'explanation' => 'We accept multiple payment methods: 1) Credit and Debit Cards (Visa, MasterCard, RuPay), 2) Net Banking from all major banks, 3) UPI payments (Google Pay, PhonePe, Paytm), 4) Digital wallets, 5) EMI options for course packages. All payments are secured with SSL encryption and processed through trusted payment gateways.',
            'hint' => 'Choose the payment method that\'s most convenient for you',
            'subject_id' => $mathSubject->id,
            'topic' => 'Payment Methods',
            'category' => 'quiz',
            'tags' => ['faq', 'payment', 'billing', 'security', 'platform'],
            'status' => 'published',
            'created_by' => $admin->id,
            'is_public' => true,
        ]);

        Question::create([
            'title' => 'Can I get a refund if I\'m not satisfied with a teacher?',
            'question_text' => 'What is the refund policy for classes and courses?',
            'type' => 'short_answer',
            'difficulty' => 'easy',
            'marks' => 2,
            'explanation' => 'Our refund policy: 1) Full refund if you cancel within 24 hours of booking, 2) 50% refund if teacher cancels the class, 3) Option to switch teachers if not satisfied after trial class, 4) Unused class credits can be transferred to another teacher, 5) Refund processing takes 5-7 business days. Contact support for assistance with refund requests.',
            'hint' => 'Try trial classes first to ensure teacher compatibility',
            'subject_id' => $mathSubject->id,
            'topic' => 'Refund Policy',
            'category' => 'quiz',
            'tags' => ['faq', 'refund', 'policy', 'satisfaction', 'support'],
            'status' => 'published',
            'created_by' => $admin->id,
            'is_public' => true,
        ]);

        $this->command->info('FAQ questions created successfully!');
    }
}
