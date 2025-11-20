<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        // Create Categories
        $categories = [
            [
                'name' => 'General Knowledge',
                'description' => 'Test your general knowledge with these quizzes',
                'image' => null,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Science',
                'description' => 'Explore the world of science',
                'image' => null,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Mathematics',
                'description' => 'Challenge your math skills',
                'image' => null,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create($categoryData);

            // Create quizzes for each category
            $this->createQuizzesForCategory($category);
        }
    }

    private function createQuizzesForCategory(Category $category): void
    {
        $quizzes = [
            [
                'title' => 'Basic '.$category->name,
                'description' => 'Basic level quiz for '.$category->name,
                'type' => 'multiple_choice',
                'time_limit' => 600, // 10 minutes
                'points' => 100,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Advanced '.$category->name,
                'description' => 'Advanced level quiz for '.$category->name,
                'type' => 'single_choice',
                'time_limit' => 900, // 15 minutes
                'points' => 200,
                'is_active' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($quizzes as $quizData) {
            $quiz = Quiz::create(array_merge($quizData, ['category_id' => $category->id]));

            // Create questions for each quiz
            $this->createQuestionsForQuiz($quiz);
        }
    }

    private function createQuestionsForQuiz(Quiz $quiz): void
    {
        $questions = [];

        if ($quiz->type === 'multiple_choice') {
            $questions = [
                [
                    'question' => 'What is the capital of France?',
                    'options' => ['London', 'Berlin', 'Paris', 'Madrid'],
                    'correct_answer' => 'Paris',
                    'points' => 10,
                    'sort_order' => 1,
                ],
                [
                    'question' => 'Which planet is known as the Red Planet?',
                    'options' => ['Venus', 'Mars', 'Jupiter', 'Saturn'],
                    'correct_answer' => 'Mars',
                    'points' => 10,
                    'sort_order' => 2,
                ],
            ];
        } elseif ($quiz->type === 'single_choice') {
            $questions = [
                [
                    'question' => 'The chemical symbol for gold is Au.',
                    'options' => null,
                    'correct_answer' => 'True',
                    'points' => 20,
                    'sort_order' => 1,
                ],
                [
                    'question' => 'Water boils at 100 degrees Celsius at sea level.',
                    'options' => null,
                    'correct_answer' => 'True',
                    'points' => 20,
                    'sort_order' => 2,
                ],
            ];
        }

        foreach ($questions as $questionData) {
            Question::create(array_merge($questionData, ['quiz_id' => $quiz->id]));
        }
    }
}
