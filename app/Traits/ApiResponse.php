<?php
// Create file: app/Traits/ApiResponse.php

namespace App\Traits;

trait ApiResponse
{
    protected function successResponse($data = null, $message = 'Success', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function errorResponse($message = 'Error', $code = 400, $errors = null)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    protected function validationErrorResponse($errors, $message = 'Validation Error')
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], 422);
    }

    protected function notFoundResponse($message = 'Resource not found')
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 404);
    }

    protected function unauthorizedResponse($message = 'Unauthorized')
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 401);
    }

    protected function forbiddenResponse($message = 'Forbidden')
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 403);
    }
}