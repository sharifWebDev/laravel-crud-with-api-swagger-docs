<?php

namespace App\Repositories\Implementations;

use App\Models\Category;
use App\Models\Quiz;
use App\Repositories\Contracts\QuizRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class QuizRepository implements QuizRepositoryInterface
{
    public function findById($id): ?Quiz
    {
        return Quiz::with(['category', 'questions' => function ($query) {
            $query->orderBy('sort_order');
        }])->where('is_active', true)->find($id);
    }

    public function getByCategory($categoryId, int $perPage = 15): LengthAwarePaginator
    {
        return Quiz::withCount('questions')
            ->where('category_id', $categoryId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->paginate($perPage);
    }

    public function search(string $query, $categoryId = null, int $page = 1, int $perPage = 10): array
    {
        $searchQuery = Quiz::with(['category'])
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            });

        if ($categoryId) {
            $searchQuery->where('category_id', $categoryId);
        }

        $results = $searchQuery->orderBy('title')
            ->paginate($perPage, ['*'], 'page', $page);

        return [
            'data' => $results->items(),
            'meta' => [
                'current_page' => $results->currentPage(),
                'per_page' => $results->perPage(),
                'total' => $results->total(),
                'last_page' => $results->lastPage(),
            ],
        ];
    }

    public function getCategoriesWithQuizCount(): array
    {
        return Category::withCount(['quizzes' => function ($query) {
            $query->where('is_active', true);
        }])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    public function getQuizWithQuestions($quizId): ?Quiz
    {
        return Quiz::with(['questions' => function ($query) {
            $query->orderBy('sort_order');
        }])
            ->where('is_active', true)
            ->find($quizId);
    }
}
