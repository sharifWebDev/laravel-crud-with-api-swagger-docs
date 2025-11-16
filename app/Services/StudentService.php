<?php

namespace App\Services;

use App\Models\Student;
use App\Repositories\Contracts\StudentRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class StudentService
{
    public function __construct(
        protected StudentRepositoryInterface $studentRepository
    ) {}

    public function getAllStudents(Request $request): LengthAwarePaginator
    {
        $length = $request->input('length', 10);
        $search = $request->input('search');
        $status = $request->input('status');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $sortColumnIndex = $request->input('order.0.column');
        $sortDirection = $request->input('order.0.dir', 'desc');

        $columns = [
            0 => 'id',
            1 => 'name',
            2 => 'roll',
            3 => 'is_active',
            4 => 'created_by',
            5 => 'updated_by',
        ];

        $sortColumn = $columns[$sortColumnIndex] ?? 'id';

        $query = $this->studentRepository->get($request);

        $query
            ->when($search && is_string($search), function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    foreach ((new Student)->getFillable() as $column) {
                        $q->orWhere($column, 'like', "%{$search}%");
                    }
                });
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            });

        $query->orderBy($sortColumn, $sortDirection);

        return $length === -1
            ? $query->paginate($query->get()->count())
            : $query->paginate($length);
    }

    public function getStudentById(int $id): ?Student
    {
        $student = $this->studentRepository->find($id);
        if (! $student) {
            throw new ModelNotFoundException;
        }

        return $student;
    }

    public function storeStudent(array $data): Student
    {
        return $this->studentRepository->create($data);
    }

    public function updateStudent(int $id, array $data): Student
    {
        return $this->studentRepository->update($id, $data);
    }

    public function deleteStudent(int $id): bool
    {
        return $this->studentRepository->delete($id);
    }
}
