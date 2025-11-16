<?php

namespace App\Http\Controllers\Api\V1;

use App\Dtos\StudentDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Resources\StudentResource;
use App\Services\StudentService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Students",
 *     description="API Endpoints for Students Management"
 * )
 */
class StudentController extends Controller
{
    public function __construct(
        protected StudentService $studentService
    ) {}
/**
 * @OA\Get(
 *     path="/api/v1/students",
 *     operationId="getStudentsList",
 *     tags={"Students"},
 *     summary="Get list of students",
 *     description="Returns paginated list of students",
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="Page number",
 *         required=false,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Number of items per page",
 *         required=false,
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *     @OA\Parameter(
 *         name="_",
 *         in="query",
 *         description="Cache-busting timestamp (optional)",
 *         required=false,
 *         @OA\Schema(type="integer", example=1763281133096)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Records retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Records retrieved successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="John Doe"),
 *                     @OA\Property(property="email", type="string", example="john@example.com")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
    public function index(Request $request): JsonResponse
    {
        try {
            $students = $this->studentService->getAllStudents($request);
            return success('Records retrieved successfully', StudentResource::collection($students));
        } catch (Exception $e) {
            info('Error retrieved Student!', [$e]);
            return error('Students retrieved failed!.');
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/students",
     *     operationId="storeStudent",
     *     tags={"Students"},
     *     summary="Create a new student",
     *     description="Store a new student in the database",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Records saved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Records saved successfully")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function store(StoreStudentRequest $request): JsonResponse
    {
        try {
            $dto = new StudentDto($request->validated());
            $student = $this->studentService->storeStudent($dto->toArray());
            return success('Records saved successfully');
        } catch (Exception $e) {
            info('Students data insert failed!', [$e]);
            return error('Students insert failed!.');
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/students/{id}",
     *     operationId="getStudentById",
     *     tags={"Students"},
     *     summary="Get student details",
     *     description="Get a student by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Student ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Student retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Records retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john@example.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Student not found")
     * )
     */
    public function find(int $id): JsonResponse
    {
        try {
            $student = $this->studentService->getStudentById($id);
            return success('Records retrieved successfully', new StudentResource($student));
        } catch (Exception $e) {
            info('Students data showing failed!', [$e]);
            return error('Students retrieval failed!');
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/students/{id}",
     *     operationId="updateStudent",
     *     tags={"Students"},
     *     summary="Update student",
     *     description="Update an existing student",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Student ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Records updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Records updated successfully")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function update(UpdateStudentRequest $request, int $id): JsonResponse
    {
        try {
            $student = $this->studentService->getStudentById($id);
            $dto = new StudentDto($request->validated());
            $this->studentService->updateStudent($student->id, $dto->toArray());
            return success('Records updated successfully', new StudentResource($student));
        } catch (Exception $e) {
            info('Students update failed!', [$e]);
            return error('Students update failed!.');
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/students/{id}",
     *     operationId="deleteStudent",
     *     tags={"Students"},
     *     summary="Delete a student",
     *     description="Delete a student by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Student ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Student deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Records deleted successfully")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Student not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $student = $this->studentService->getStudentById($id);
            $this->studentService->deleteStudent($student->id);
            return success('Records deleted successfully');
        } catch (Exception $e) {
            info('Students delete failed!', [$e]);
            return error('Students delete failed!.');
        }
    }
}
