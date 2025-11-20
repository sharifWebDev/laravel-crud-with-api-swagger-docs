<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Quiz\SubmitQuizRequest;
use App\Services\Contracts\QuizServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class QuizController extends Controller
{
    public function __construct(private QuizServiceInterface $quizService) {}

    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Get all categories",
     *     description="Retrieve all active quiz categories with quiz counts",
     *     tags={"Quiz"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Categories retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Categories retrieved successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="categories", type="array",
     *
     *                     @OA\Items(type="object",
     *
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="General Knowledge"),
     *                         @OA\Property(property="description", type="string", example="Test your general knowledge"),
     *                         @OA\Property(property="image", type="string", nullable=true),
     *                         @OA\Property(property="quizzes_count", type="integer", example=5),
     *                         @OA\Property(property="created_at", type="string", format="date-time")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Failed to retrieve categories",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve categories: Error message")
     *         )
     *     )
     * )
     */
    public function categories(Request $request): JsonResponse
    {
        try {
            $categories = $this->quizService->getAllCategories();

            return response()->json([
                'status' => 'success',
                'message' => 'Categories retrieved successfully.',
                'data' => [
                    'categories' => $categories,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve categories: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{categoryId}/quizzes",
     *     summary="Get quizzes by category",
     *     description="Retrieve all active quizzes for a specific category",
     *     tags={"Quiz"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="categoryId",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Quizzes retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Quizzes retrieved successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="quizzes", type="array",
     *
     *                     @OA\Items(type="object",
     *
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Basic General Knowledge"),
     *                         @OA\Property(property="description", type="string", example="Test your basic knowledge"),
     *                         @OA\Property(property="type", type="string", enum={"multiple_choice", "true_false", "single_choice"}, example="multiple_choice"),
     *                         @OA\Property(property="time_limit", type="integer", nullable=true, example=600),
     *                         @OA\Property(property="points", type="integer", example=100),
     *                         @OA\Property(property="questions_count", type="integer", example=10),
     *                         @OA\Property(property="created_at", type="string", format="date-time")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Category not found.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Failed to retrieve quizzes",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve quizzes: Error message")
     *         )
     *     )
     * )
     */
    public function quizzesByCategory(Request $request, $categoryId): JsonResponse
    {
        try {
            $quizzes = $this->quizService->getQuizzesByCategory($categoryId);

            return response()->json([
                'status' => 'success',
                'message' => 'Quizzes retrieved successfully.',
                'data' => [
                    'quizzes' => $quizzes,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve quizzes: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/quizzes/{quizId}",
     *     summary="Get quiz details with questions",
     *     description="Retrieve detailed quiz information including all questions",
     *     tags={"Quiz"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="quizId",
     *         in="path",
     *         required=true,
     *         description="Quiz ID",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Quiz retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Quiz retrieved successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="quiz", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Basic General Knowledge"),
     *                     @OA\Property(property="description", type="string", example="Test your basic knowledge"),
     *                     @OA\Property(property="type", type="string", example="multiple_choice"),
     *                     @OA\Property(property="time_limit", type="integer", example=600),
     *                     @OA\Property(property="points", type="integer", example=100),
     *                     @OA\Property(property="category", type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="General Knowledge")
     *                     ),
     *                     @OA\Property(property="questions", type="array",
     *
     *                         @OA\Items(type="object",
     *
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="question", type="string", example="What is the capital of France?"),
     *                             @OA\Property(property="options", type="array", @OA\Items(type="string", example="Paris")),
     *                             @OA\Property(property="points", type="integer", example=10),
     *                             @OA\Property(property="sort_order", type="integer", example=1)
     *                         )
     *                     ),
     *                     @OA\Property(property="total_questions", type="integer", example=10),
     *                     @OA\Property(property="total_points", type="integer", example=100)
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Quiz not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Quiz not found.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Failed to retrieve quiz",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve quiz: Error message")
     *         )
     *     )
     * )
     */
    public function show(Request $request, $quizId): JsonResponse
    {
        try {
            $quiz = $this->quizService->getQuizWithQuestions($quizId);

            if (! $quiz) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Quiz not found.',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Quiz retrieved successfully.',
                'data' => [
                    'quiz' => $quiz,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve quiz: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/quizzes/{quizId}/submit",
     *     summary="Submit quiz answers",
     *     description="Submit quiz answers and get results with scoring",
     *     tags={"Quiz"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="quizId",
     *         in="path",
     *         required=true,
     *         description="Quiz ID",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"answers", "time_taken"},
     *
     *             @OA\Property(property="answers", type="array",
     *
     *                 @OA\Items(type="object",
     *
     *                     @OA\Property(property="question_id", type="integer", example=1),
     *                     @OA\Property(property="answer", type="string", example="Paris")
     *                 )
     *             ),
     *             @OA\Property(property="time_taken", type="integer", example=300, description="Time taken in seconds")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Quiz submitted successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Quiz submitted successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="result_id", type="integer", example=1),
     *                 @OA\Property(property="quiz_title", type="string", example="Basic General Knowledge"),
     *                 @OA\Property(property="score", type="integer", example=80),
     *                 @OA\Property(property="total_possible_points", type="integer", example=100),
     *                 @OA\Property(property="percentage_score", type="number", format="float", example=80.0),
     *                 @OA\Property(property="correct_answers", type="integer", example=8),
     *                 @OA\Property(property="total_questions", type="integer", example=10),
     *                 @OA\Property(property="time_taken", type="integer", example=300),
     *                 @OA\Property(property="answers", type="array",
     *
     *                     @OA\Items(type="object",
     *
     *                         @OA\Property(property="question_id", type="integer", example=1),
     *                         @OA\Property(property="question", type="string", example="What is the capital of France?"),
     *                         @OA\Property(property="user_answer", type="string", example="Paris"),
     *                         @OA\Property(property="correct_answer", type="string", example="Paris"),
     *                         @OA\Property(property="is_correct", type="boolean", example=true),
     *                         @OA\Property(property="points", type="integer", example=10),
     *                         @OA\Property(property="explanation", type="string", nullable=true)
     *                     )
     *                 ),
     *                 @OA\Property(property="submitted_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Failed to submit quiz",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to submit quiz: Error message")
     *         )
     *     )
     * )
     */
    public function submit(SubmitQuizRequest $request, $quizId): JsonResponse
    {
        try {
            $user = $request->user();
            $answers = $request->input('answers', []);
            $timeTaken = $request->input('time_taken', 0);

            $result = $this->quizService->submitQuiz($user, $quizId, $answers, $timeTaken);

            return response()->json([
                'status' => 'success',
                'message' => 'Quiz submitted successfully.',
                'data' => $result,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to submit quiz: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/quiz-results",
     *     summary="Get user's quiz results",
     *     description="Retrieve paginated list of user's quiz results",
     *     tags={"Quiz"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Page number",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Number of items per page",
     *
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Quiz results retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Quiz results retrieved successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="data", type="array",
     *
     *                     @OA\Items(type="object",
     *
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="quiz", type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="title", type="string", example="Basic General Knowledge"),
     *                             @OA\Property(property="category", type="string", example="General Knowledge")
     *                         ),
     *                         @OA\Property(property="score", type="integer", example=80),
     *                         @OA\Property(property="correct_answers", type="integer", example=8),
     *                         @OA\Property(property="total_questions", type="integer", example=10),
     *                         @OA\Property(property="time_taken", type="integer", example=300),
     *                         @OA\Property(property="percentage_score", type="number", format="float", example=80.0),
     *                         @OA\Property(property="submitted_at", type="string", format="date-time")
     *                     )
     *                 ),
     *                 @OA\Property(property="meta", type="object",
     *                     @OA\Property(property="current_page", type="integer", example=1),
     *                     @OA\Property(property="per_page", type="integer", example=10),
     *                     @OA\Property(property="total", type="integer", example=50)
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Failed to retrieve quiz results",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve quiz results: Error message")
     *         )
     *     )
     * )
     */
    public function results(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $page = $request->input('page', 1);
            $perPage = $request->input('per_page', 10);

            $results = $this->quizService->getUserResults($user, $page, $perPage);

            return response()->json([
                'status' => 'success',
                'message' => 'Quiz results retrieved successfully.',
                'data' => $results,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve quiz results: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/quiz-results/{resultId}",
     *     summary="Get quiz result details",
     *     description="Retrieve detailed information about a specific quiz result",
     *     tags={"Quiz"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="resultId",
     *         in="path",
     *         required=true,
     *         description="Quiz Result ID",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Quiz result details retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Quiz result details retrieved successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="result", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="quiz", type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Basic General Knowledge"),
     *                         @OA\Property(property="description", type="string", example="Test your basic knowledge"),
     *                         @OA\Property(property="type", type="string", example="multiple_choice")
     *                     ),
     *                     @OA\Property(property="score", type="integer", example=80),
     *                     @OA\Property(property="correct_answers", type="integer", example=8),
     *                     @OA\Property(property="total_questions", type="integer", example=10),
     *                     @OA\Property(property="time_taken", type="integer", example=300),
     *                     @OA\Property(property="percentage_score", type="number", format="float", example=80.0),
     *                     @OA\Property(property="answers", type="array",
     *
     *                         @OA\Items(type="object",
     *
     *                             @OA\Property(property="question_id", type="integer", example=1),
     *                             @OA\Property(property="question", type="string", example="What is the capital of France?"),
     *                             @OA\Property(property="user_answer", type="string", example="Paris"),
     *                             @OA\Property(property="correct_answer", type="string", example="Paris"),
     *                             @OA\Property(property="is_correct", type="boolean", example=true),
     *                             @OA\Property(property="points", type="integer", example=10),
     *                             @OA\Property(property="explanation", type="string", nullable=true)
     *                         )
     *                     ),
     *                     @OA\Property(property="submitted_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Quiz result not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Quiz result not found.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Failed to retrieve quiz result details",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve quiz result details: Error message")
     *         )
     *     )
     * )
     */
    public function resultDetails(Request $request, $resultId): JsonResponse
    {
        try {
            $user = $request->user();
            $result = $this->quizService->getResultDetails($user, $resultId);

            if (! $result) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Quiz result not found.',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Quiz result details retrieved successfully.',
                'data' => [
                    'result' => $result,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve quiz result details: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/quizzes/{quizId}/leaderboard",
     *     summary="Get quiz leaderboard",
     *     description="Retrieve top performers for a specific quiz",
     *     tags={"Quiz"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="quizId",
     *         in="path",
     *         required=true,
     *         description="Quiz ID",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         required=false,
     *         description="Number of top performers to return",
     *
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Leaderboard retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Leaderboard retrieved successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="leaderboard", type="array",
     *
     *                     @OA\Items(type="object",
     *
     *                         @OA\Property(property="rank", type="integer", example=1),
     *                         @OA\Property(property="user", type="object",
     *                             @OA\Property(property="user_id", type="string", example="60839236"),
     *                             @OA\Property(property="full_name", type="string", example="John Doe"),
     *                             @OA\Property(property="profile_img", type="string", nullable=true)
     *                         ),
     *                         @OA\Property(property="best_score", type="integer", example=100),
     *                         @OA\Property(property="best_time", type="integer", example=250),
     *                         @OA\Property(property="attempts", type="integer", example=3),
     *                         @OA\Property(property="last_attempt", type="string", format="date-time")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Failed to retrieve leaderboard",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve leaderboard: Error message")
     *         )
     *     )
     * )
     */
    public function leaderboard(Request $request, $quizId): JsonResponse
    {
        try {
            $limit = $request->input('limit', 10);
            $leaderboard = $this->quizService->getQuizLeaderboard($quizId, $limit);

            return response()->json([
                'status' => 'success',
                'message' => 'Leaderboard retrieved successfully.',
                'data' => [
                    'leaderboard' => $leaderboard,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve leaderboard: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/quizzes/search",
     *     summary="Search quizzes",
     *     description="Search quizzes by title or description with optional category filter",
     *     tags={"Quiz"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         required=false,
     *         description="Search query",
     *
     *         @OA\Schema(type="string", example="general knowledge")
     *     ),
     *
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         required=false,
     *         description="Filter by category ID",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Page number",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Number of items per page",
     *
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Quizzes search completed successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Quizzes search completed successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="quizzes", type="array",
     *
     *                     @OA\Items(type="object",
     *
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Basic General Knowledge"),
     *                         @OA\Property(property="description", type="string", example="Test your basic knowledge"),
     *                         @OA\Property(property="type", type="string", example="multiple_choice"),
     *                         @OA\Property(property="category", type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="General Knowledge")
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(property="pagination", type="object",
     *                     @OA\Property(property="current_page", type="integer", example=1),
     *                     @OA\Property(property="per_page", type="integer", example=10),
     *                     @OA\Property(property="total", type="integer", example=25),
     *                     @OA\Property(property="last_page", type="integer", example=3)
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Failed to search quizzes",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to search quizzes: Error message")
     *         )
     *     )
     * )
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->input('query', '');
            $categoryId = $request->input('category_id');
            $page = $request->input('page', 1);
            $perPage = $request->input('per_page', 10);

            $results = $this->quizService->searchQuizzes($query, $categoryId, $page, $perPage);

            return response()->json([
                'status' => 'success',
                'message' => 'Quizzes search completed successfully.',
                'data' => $results,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to search quizzes: '.$e->getMessage(),
            ], 500);
        }
    }
}
