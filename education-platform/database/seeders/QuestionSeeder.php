<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Subject;
use App\Models\User;

class QuestionSeeder extends Seeder
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

        // Mathematics Questions
        $mathSubject = $subjects->where('name', 'like', '%Math%')->first() ?? $subjects->first();
        
        Question::create([
            'title' => 'Basic Algebra - Linear Equations',
            'question_text' => 'Solve for x: 2x + 5 = 13',
            'type' => 'multiple_choice',
            'difficulty' => 'easy',
            'marks' => 2,
            'time_limit' => 120,
            'options' => ['x = 3', 'x = 4', 'x = 5', 'x = 6'],
            'correct_answers' => [1], // x = 4
            'explanation' => 'Subtract 5 from both sides: 2x = 8, then divide by 2: x = 4',
            'hint' => 'Isolate the variable x by performing inverse operations',
            'subject_id' => $mathSubject->id,
            'topic' => 'Linear Equations',
            'chapter' => 'Algebra Basics',
            'class_level' => '8',
            'board' => 'CBSE',
            'category' => 'practice',
            'status' => 'published',
            'created_by' => $admin->id,
            'is_public' => true,
        ]);

        Question::create([
            'title' => 'Quadratic Formula',
            'question_text' => 'What is the discriminant of the quadratic equation 3x² - 5x + 2 = 0?',
            'type' => 'short_answer',
            'difficulty' => 'medium',
            'marks' => 3,
            'time_limit' => 180,
            'correct_answers' => ['1'],
            'explanation' => 'Discriminant = b² - 4ac = (-5)² - 4(3)(2) = 25 - 24 = 1',
            'solution_steps' => 'Step 1: Identify a=3, b=-5, c=2\nStep 2: Apply formula Δ = b² - 4ac\nStep 3: Δ = 25 - 24 = 1',
            'subject_id' => $mathSubject->id,
            'topic' => 'Quadratic Equations',
            'chapter' => 'Polynomials',
            'class_level' => '10',
            'board' => 'CBSE',
            'category' => 'exam',
            'status' => 'published',
            'created_by' => $admin->id,
            'is_public' => true,
        ]);

        // Science Questions
        $scienceSubject = $subjects->where('name', 'like', '%Physics%')->first() ?? 
                         $subjects->where('name', 'like', '%Science%')->first() ?? $mathSubject;
        
        Question::create([
            'title' => 'Newton\'s First Law',
            'question_text' => 'An object at rest will remain at rest unless acted upon by an external force. This statement represents which law?',
            'type' => 'multiple_choice',
            'difficulty' => 'easy',
            'marks' => 1,
            'time_limit' => 60,
            'options' => ['Newton\'s First Law', 'Newton\'s Second Law', 'Newton\'s Third Law', 'Law of Gravitation'],
            'correct_answers' => [0],
            'explanation' => 'This is the definition of Newton\'s First Law of Motion, also known as the Law of Inertia.',
            'subject_id' => $scienceSubject->id,
            'topic' => 'Laws of Motion',
            'chapter' => 'Force and Motion',
            'class_level' => '9',
            'board' => 'CBSE',
            'category' => 'practice',
            'status' => 'published',
            'created_by' => $admin->id,
            'is_public' => true,
        ]);

        Question::create([
            'title' => 'Photosynthesis Process',
            'question_text' => 'What are the main products of photosynthesis?',
            'type' => 'multiple_choice',
            'difficulty' => 'easy',
            'marks' => 2,
            'options' => ['Glucose and Oxygen', 'Carbon Dioxide and Water', 'Nitrogen and Phosphorus', 'Glucose and Carbon Dioxide'],
            'correct_answers' => [0],
            'explanation' => 'Photosynthesis produces glucose (C6H12O6) and oxygen (O2) from carbon dioxide and water using sunlight.',
            'subject_id' => $scienceSubject->id,
            'topic' => 'Photosynthesis',
            'chapter' => 'Life Processes',
            'class_level' => '10',
            'board' => 'CBSE',
            'category' => 'practice',
            'status' => 'published',
            'created_by' => $admin->id,
            'is_public' => true,
        ]);

        // English Questions
        $englishSubject = $subjects->where('name', 'like', '%English%')->first() ?? $mathSubject;
        
        Question::create([
            'title' => 'Parts of Speech - Adjectives',
            'question_text' => 'Identify the adjective in the sentence: "The beautiful garden has many colorful flowers."',
            'type' => 'multiple_choice',
            'difficulty' => 'easy',
            'marks' => 1,
            'options' => ['beautiful', 'garden', 'many', 'flowers'],
            'correct_answers' => [0],
            'explanation' => '"Beautiful" is an adjective that describes the noun "garden". "Many" and "colorful" are also adjectives in this sentence.',
            'subject_id' => $englishSubject->id,
            'topic' => 'Parts of Speech',
            'chapter' => 'Grammar',
            'class_level' => '6',
            'board' => 'CBSE',
            'category' => 'practice',
            'status' => 'published',
            'created_by' => $admin->id,
            'is_public' => true,
        ]);

        Question::create([
            'title' => 'Literary Devices - Metaphor',
            'question_text' => 'Write an example of a metaphor and explain its meaning.',
            'type' => 'long_answer',
            'difficulty' => 'medium',
            'marks' => 5,
            'time_limit' => 300,
            'explanation' => 'A metaphor is a figure of speech that directly compares two different things without using "like" or "as". Example: "Life is a journey" - this compares life to a journey, suggesting that life has a path, destinations, and experiences along the way.',
            'subject_id' => $englishSubject->id,
            'topic' => 'Literary Devices',
            'chapter' => 'Poetry and Prose',
            'class_level' => '11',
            'board' => 'CBSE',
            'category' => 'exam',
            'status' => 'published',
            'created_by' => $admin->id,
            'is_public' => true,
        ]);

        // True/False Questions
        Question::create([
            'title' => 'Earth\'s Rotation',
            'question_text' => 'The Earth completes one rotation on its axis in approximately 24 hours.',
            'type' => 'true_false',
            'difficulty' => 'easy',
            'marks' => 1,
            'options' => ['True', 'False'],
            'correct_answers' => [0], // True
            'explanation' => 'The Earth takes approximately 24 hours (23 hours 56 minutes to be precise) to complete one rotation on its axis.',
            'subject_id' => $scienceSubject->id,
            'topic' => 'Earth and Universe',
            'chapter' => 'Our Solar System',
            'class_level' => '8',
            'board' => 'CBSE',
            'category' => 'quiz',
            'status' => 'published',
            'created_by' => $admin->id,
            'is_public' => true,
        ]);

        // Fill in the blank
        Question::create([
            'title' => 'Chemical Formula',
            'question_text' => 'The chemical formula for water is _______.',
            'type' => 'fill_blank',
            'difficulty' => 'easy',
            'marks' => 1,
            'correct_answers' => ['H2O', 'h2o'],
            'explanation' => 'Water consists of two hydrogen atoms and one oxygen atom, hence H2O.',
            'subject_id' => $scienceSubject->id,
            'topic' => 'Chemical Formulas',
            'chapter' => 'Atoms and Molecules',
            'class_level' => '9',
            'board' => 'CBSE',
            'category' => 'practice',
            'status' => 'published',
            'created_by' => $admin->id,
            'is_public' => true,
        ]);

        // Draft and Under Review Questions
        Question::create([
            'title' => 'Advanced Calculus Problem',
            'question_text' => 'Find the derivative of f(x) = x³ + 2x² - 5x + 3',
            'type' => 'short_answer',
            'difficulty' => 'hard',
            'marks' => 4,
            'time_limit' => 240,
            'correct_answers' => ['3x² + 4x - 5'],
            'explanation' => 'Using power rule: f\'(x) = 3x² + 4x - 5',
            'subject_id' => $mathSubject->id,
            'topic' => 'Differentiation',
            'chapter' => 'Calculus',
            'class_level' => '12',
            'board' => 'CBSE',
            'category' => 'exam',
            'status' => 'draft',
            'created_by' => $admin->id,
            'is_public' => false,
        ]);

        Question::create([
            'title' => 'Complex Numbers',
            'question_text' => 'What is the modulus of the complex number 3 + 4i?',
            'type' => 'short_answer',
            'difficulty' => 'medium',
            'marks' => 2,
            'correct_answers' => ['5'],
            'explanation' => 'Modulus = √(3² + 4²) = √(9 + 16) = √25 = 5',
            'subject_id' => $mathSubject->id,
            'topic' => 'Complex Numbers',
            'chapter' => 'Algebra',
            'class_level' => '11',
            'board' => 'CBSE',
            'category' => 'practice',
            'status' => 'under_review',
            'created_by' => $admin->id,
            'is_public' => true,
        ]);

        // Add some tags and more detailed questions
        Question::create([
            'title' => 'Probability and Statistics',
            'question_text' => 'A bag contains 5 red balls, 3 blue balls, and 2 green balls. What is the probability of drawing a red ball?',
            'type' => 'multiple_choice',
            'difficulty' => 'medium',
            'marks' => 3,
            'time_limit' => 150,
            'options' => ['1/2', '1/3', '5/10', '5/8'],
            'correct_answers' => [0], // 1/2
            'explanation' => 'Total balls = 5 + 3 + 2 = 10. Probability of red = 5/10 = 1/2',
            'hint' => 'Count total balls and favorable outcomes',
            'solution_steps' => 'Step 1: Count total balls\nStep 2: Count red balls\nStep 3: Apply probability formula P = favorable/total',
            'subject_id' => $mathSubject->id,
            'topic' => 'Probability',
            'chapter' => 'Statistics and Probability',
            'class_level' => '10',
            'board' => 'CBSE',
            'category' => 'exam',
            'tags' => ['probability', 'statistics', 'counting', 'fractions'],
            'source' => 'NCERT Mathematics Class 10',
            'reference' => 'Chapter 15, Exercise 15.1',
            'status' => 'published',
            'created_by' => $admin->id,
            'is_public' => true,
        ]);

        $this->command->info('Sample questions created successfully!');
    }
}
