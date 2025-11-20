<?php

namespace App\Services\Implementations;

use App\Models\Category;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\User;
use App\Repositories\Contracts\QuizRepositoryInterface;
use App\Services\Contracts\QuizServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class QuizService implements QuizServiceInterface
{
    public function __construct(private QuizRepositoryInterface $quizRepository) {}

    public function getAllCategories(): array
    {
        $categories = Category::withCount(['quizzes' => function ($query) {
            $query->where('is_active', true);
        }])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'image' => $category->image,
                'quizzes_count' => $category->quizzes_count,
                'created_at' => $category->created_at->toISOString(),
            ];
        })->toArray();
    }

    public function getQuizzesByCategory($categoryId): array
    {
        $category = Category::where('is_active', true)->findOrFail($categoryId);

        $quizzes = Quiz::withCount('questions')
            ->where('category_id', $categoryId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        return [
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'image' => $category->image,
            ],
            'quizzes' => $quizzes->map(function ($quiz) {
                return [
                    'id' => $quiz->id,
                    'title' => $quiz->title,
                    'description' => $quiz->description,
                    'type' => $quiz->type,
                    'time_limit' => $quiz->time_limit,
                    'points' => $quiz->points,
                    'questions_count' => $quiz->questions_count,
                    'created_at' => $quiz->created_at->toISOString(),
                ];
            })->toArray(),
        ];
    }

    public function getQuizWithQuestions($quizId): ?array
    {
        $quiz = Quiz::with(['questions' => function ($query) {
            $query->orderBy('sort_order');
        }])
            ->where('is_active', true)
            ->find($quizId);

        if (! $quiz) {
            return null;
        }

        return [
            'id' => $quiz->id,
            'title' => $quiz->title,
            'description' => $quiz->description,
            'type' => $quiz->type,
            'time_limit' => $quiz->time_limit,
            'points' => $quiz->points,
            'category' => [
                'id' => $quiz->category->id,
                'name' => $quiz->category->name,
            ],
            'questions' => $quiz->questions->map(function ($question) use ($quiz) {
                $questionData = [
                    'id' => $question->id,
                    'question' => $question->question,
                    'points' => $question->points,
                    'sort_order' => $question->sort_order,
                ];

                // For multiple choice questions, include options
                if (in_array($quiz->type, ['multiple_choice', 'single_choice'])) {
                    $questionData['options'] = $question->options;
                }

                // For true/false questions, include the options
                if ($quiz->type === 'true_false') {
                    $questionData['options'] = ['True', 'False'];
                }

                return $questionData;
            })->toArray(),
            'total_questions' => $quiz->questions->count(),
            'total_points' => $quiz->questions->sum('points'),
        ];
    }

    public function submitQuiz(User $user, $quizId, array $answers, int $timeTaken): array
    {
        return DB::transaction(function () use ($user, $quizId, $answers, $timeTaken) {
            $quiz = Quiz::with('questions')->where('is_active', true)->findOrFail($quizId);

            $score = 0;
            $correctAnswers = 0;
            $userAnswers = [];

            foreach ($answers as $answerData) {
                $question = $quiz->questions->firstWhere('id', $answerData['question_id']);

                if (! $question) {
                    continue;
                }

                $isCorrect = $question->isCorrectAnswer($answerData['answer']);

                if ($isCorrect) {
                    $score += $question->points;
                    $correctAnswers++;
                }

                $userAnswers[] = [
                    'question_id' => $question->id,
                    'question' => $question->question,
                    'user_answer' => $answerData['answer'],
                    'correct_answer' => $question->correct_answer,
                    'is_correct' => $isCorrect,
                    'points' => $isCorrect ? $question->points : 0,
                    'explanation' => $question->explanation,
                ];
            }

            // Calculate percentage score
            $totalPossiblePoints = $quiz->questions->sum('points');
            $percentageScore = $totalPossiblePoints > 0 ? ($score / $totalPossiblePoints) * 100 : 0;

            // Create quiz result
            $quizResult = QuizResult::create([
                'user_id' => $user->id,
                'quiz_id' => $quiz->id,
                'score' => $score,
                'total_questions' => $quiz->questions->count(),
                'correct_answers' => $correctAnswers,
                'time_taken' => $timeTaken,
                'answers' => $userAnswers,
            ]);

            return [
                'result_id' => $quizResult->id,
                'quiz_title' => $quiz->title,
                'score' => $score,
                'total_possible_points' => $totalPossiblePoints,
                'percentage_score' => round($percentageScore, 2),
                'correct_answers' => $correctAnswers,
                'total_questions' => $quiz->questions->count(),
                'time_taken' => $timeTaken,
                'answers' => $userAnswers,
                'submitted_at' => $quizResult->created_at->toISOString(),
            ];
        });
    }

    public function getUserResults(User $user, int $page = 1, int $perPage = 10): LengthAwarePaginator
    {
        $results = QuizResult::with('quiz.category')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return $results->through(function ($result) {
            return [
                'id' => $result->id,
                'quiz' => [
                    'id' => $result->quiz->id,
                    'title' => $result->quiz->title,
                    'category' => $result->quiz->category->name,
                ],
                'score' => $result->score,
                'correct_answers' => $result->correct_answers,
                'total_questions' => $result->total_questions,
                'time_taken' => $result->time_taken,
                'percentage_score' => $result->total_questions > 0 ?
                    round(($result->correct_answers / $result->total_questions) * 100, 2) : 0,
                'submitted_at' => $result->created_at->toISOString(),
            ];
        });
    }

    public function getResultDetails(User $user, $resultId): ?array
    {
        $result = QuizResult::with(['quiz', 'quiz.questions'])
            ->where('user_id', $user->id)
            ->find($resultId);

        if (! $result) {
            return null;
        }

        return [
            'id' => $result->id,
            'quiz' => [
                'id' => $result->quiz->id,
                'title' => $result->quiz->title,
                'description' => $result->quiz->description,
                'type' => $result->quiz->type,
            ],
            'score' => $result->score,
            'correct_answers' => $result->correct_answers,
            'total_questions' => $result->total_questions,
            'time_taken' => $result->time_taken,
            'percentage_score' => $result->total_questions > 0 ?
                round(($result->correct_answers / $result->total_questions) * 100, 2) : 0,
            'answers' => $result->answers,
            'submitted_at' => $result->created_at->toISOString(),
        ];
    }

    public function getQuizLeaderboard($quizId, int $limit = 10): array
    {
        $leaderboard = QuizResult::with('user')
            ->where('quiz_id', $quizId)
            ->select([
                'user_id',
                DB::raw('MAX(score) as best_score'),
                DB::raw('MIN(time_taken) as best_time'),
                DB::raw('COUNT(*) as attempts'),
                DB::raw('MAX(created_at) as last_attempt'),
            ])
            ->groupBy('user_id')
            ->orderBy('best_score', 'desc')
            ->orderBy('best_time', 'asc')
            ->limit($limit)
            ->get();

        return $leaderboard->map(function ($item, $index) {
            return [
                'rank' => $index + 1,
                'user' => [
                    'user_id' => $item->user->unique_id,
                    'full_name' => $item->user->full_name,
                    'profile_img' => $item->user->profile_img,
                ],
                'best_score' => $item->best_score,
                'best_time' => $item->best_time,
                'attempts' => $item->attempts,
                'last_attempt' => $item->last_attempt,
            ];
        })->toArray();
    }

    public function searchQuizzes(string $query, $categoryId = null, int $page = 1, int $perPage = 10): array
    {
        $searchQuery = Quiz::with(['category', 'questions'])
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            });

        if ($categoryId) {
            $searchQuery->where('category_id', $categoryId);
        }

        $quizzes = $searchQuery->orderBy('title')
            ->paginate($perPage, ['*'], 'page', $page);

        return [
            'quizzes' => $quizzes->items(),
            'pagination' => [
                'current_page' => $quizzes->currentPage(),
                'per_page' => $quizzes->perPage(),
                'total' => $quizzes->total(),
                'last_page' => $quizzes->lastPage(),
            ],
        ];
    }
}
