<?php

namespace App\Repositories\Contracts;

use App\Models\Quiz;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface QuizRepositoryInterface
{
    public function findById($id): ?Quiz;

    public function getByCategory($categoryId, int $perPage = 15): LengthAwarePaginator;

    public function search(string $query, $categoryId = null, int $page = 1, int $perPage = 10): array;

    public function getCategoriesWithQuizCount(): array;
}
