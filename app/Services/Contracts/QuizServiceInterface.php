<?php

namespace App\Services\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface QuizServiceInterface
{
    public function getAllCategories(): array;

    public function getQuizzesByCategory($categoryId): array;

    public function getQuizWithQuestions($quizId): ?array;

    public function submitQuiz(User $user, $quizId, array $answers, int $timeTaken): array;

    public function getUserResults(User $user, int $page = 1, int $perPage = 10): LengthAwarePaginator;

    public function getResultDetails(User $user, $resultId): ?array;

    public function getQuizLeaderboard($quizId, int $limit = 10): array;

    public function searchQuizzes(string $query, $categoryId = null, int $page = 1, int $perPage = 10): array;
}
