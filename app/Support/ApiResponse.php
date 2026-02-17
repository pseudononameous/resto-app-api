<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    public static function success(mixed $data = null, ?string $message = null, int $code = Response::HTTP_OK): JsonResponse
    {
        $payload = ['success' => true];

        if ($data !== null) {
            $payload['data'] = $data;
        }

        if ($message !== null) {
            $payload['message'] = $message;
        }

        return response()->json($payload, $code);
    }

    public static function successWithMeta(array $data, LengthAwarePaginator $paginator, int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ], $code);
    }

    public static function error(string $message, int $code = Response::HTTP_BAD_REQUEST, mixed $errors = null): JsonResponse
    {
        $payload = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $code);
    }

    public static function noContent(): JsonResponse
    {
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}

