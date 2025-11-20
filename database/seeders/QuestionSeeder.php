<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $quizzes = Quiz::all();

        foreach ($quizzes as $quiz) {
            $this->createQuestionsForQuiz($quiz);
        }
    }

    private function createQuestionsForQuiz(Quiz $quiz): void
    {
        $questions = [];

        if (str_contains($quiz->title, 'General Knowledge')) {
            $questions = $this->getGeneralKnowledgeQuestions();
        } elseif (str_contains($quiz->title, 'Science')) {
            $questions = $this->getScienceQuestions();
        } elseif (str_contains($quiz->title, 'Mathematics')) {
            $questions = $this->getMathematicsQuestions();
        }

        foreach ($questions as $index => $questionData) {
            Question::create(array_merge($questionData, [
                'quiz_id' => $quiz->id,
                'sort_order' => $index + 1,
            ]));
        }
    }

    private function getGeneralKnowledgeQuestions(): array
    {
        return [
            [
                'question' => 'What is the capital of France?',
                'options' => ['London', 'Berlin', 'Paris', 'Madrid'],
                'correct_answer' => 'Paris',
                'explanation' => 'Paris is the capital and most populous city of France.',
                'points' => 10,
                'question_type' => 'multiple_choice',
            ],
            [
                'question' => 'Which planet is known as the Red Planet?',
                'options' => ['Venus', 'Mars', 'Jupiter', 'Saturn'],
                'correct_answer' => 'Mars',
                'explanation' => 'Mars is often called the Red Planet because iron minerals in the Martian soil oxidize, or rust, causing the soil and atmosphere to look red.',
                'points' => 10,
                'question_type' => 'single_choice',
            ],
            [
                'question' => 'The Great Wall of China is visible from space.',
                'options' => null,
                'correct_answer' => 'False',
                'explanation' => 'This is a common myth. The Great Wall of China is not visible from space with the naked eye.',
                'points' => 15,
                'question_type' => 'true_false',
            ],
        ];
    }

    private function getScienceQuestions(): array
    {
        return [
            [
                'question' => 'What is the chemical symbol for water?',
                'options' => ['H2O', 'CO2', 'NaCl', 'O2'],
                'correct_answer' => 'H2O',
                'explanation' => 'Water is a chemical compound consisting of two hydrogen atoms and one oxygen atom.',
                'points' => 10,
                'question_type' => 'multiple_choice',
            ],
            [
                'question' => 'Photosynthesis occurs in which part of the plant cell?',
                'options' => ['Mitochondria', 'Nucleus', 'Chloroplast', 'Ribosome'],
                'correct_answer' => 'Chloroplast',
                'explanation' => 'Chloroplasts contain chlorophyll, which captures light energy for photosynthesis.',
                'points' => 15,
                'question_type' => 'single_choice',
            ],
        ];
    }

    private function getMathematicsQuestions(): array
    {
        return [
            [
                'question' => 'What is the value of π (pi) approximately?',
                'options' => ['3.14', '2.71', '1.61', '4.67'],
                'correct_answer' => '3.14',
                'explanation' => 'π is approximately 3.14159, but 3.14 is commonly used for calculations.',
                'points' => 10,
                'question_type' => 'multiple_choice',
            ],
            [
                'question' => 'Solve for x: 2x + 5 = 15',
                'options' => ['x = 5', 'x = 10', 'x = 7.5', 'x = 2.5'],
                'correct_answer' => 'x = 5',
                'explanation' => '2x + 5 = 15 → 2x = 10 → x = 5',
                'points' => 15,
                'question_type' => 'single_choice',
            ],
        ];
    }
}
