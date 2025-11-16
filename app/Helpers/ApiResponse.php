<?php

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

function success($message = 'Success', $data = [], $statusCode = 200)
{
    return response()->json([
        'success' => true,
        'status' => $statusCode,
        'message' => $message,
        'data' => $data,
    ], $statusCode);
}

function error($message = 'An error occurred', $statusCode = 500, $data = [])
{
    $response = [
        'success' => false,
        'status' => $statusCode,
        'message' => $message,
    ];

    if (! empty($data)) {
        $response['data'] = $data;
    }

    return response()->json($response, $statusCode);
}

function notFound($message = 'Resource not found')
{
    return error($message, 404);
}

function forbidden($message = 'Forbidden')
{
    return error($message, 403);
}

function validationError(ValidationException $exception, $statusCode = 422)
{
    return response()->json([
        'success' => false,
        'status' => $statusCode,
        'message' => 'Validation error',
        'errors' => $exception->errors(),
    ], $statusCode);
}

function validationErrors($errors, $statusCode = 422)
{
    return response()->json([
        'success' => false,
        'status' => $statusCode,
        'message' => 'Validation failed!',
        'errors' => $errors,
    ], $statusCode);
}

function validationThrowException($validator): JsonResponse
{
    throw new HttpResponseException(
        error(
            'Validation error',
            422,
            $validator->errors(),
        )
    );
}

function paginator($message, $model)
{

    $meta = [
        'current_page' => $model->currentPage(),
        'from' => $model->firstItem(),
        'last_page' => $model->lastPage(),
        'path' => $model->path(),
        'per_page' => $model->perPage(),
        'to' => $model->lastItem(),
        'total' => $model->total(),
    ];

    $data = [
        'data' => $model,
        'meta' => $meta,
    ];

    return success($message, $data);

}
