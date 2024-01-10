<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    public static function successResponse(mixed $data = null, string $message = '', int $code = Response::HTTP_OK, array $additional = [], array $headers = []): \Illuminate\Http\JsonResponse
    {
        return self::apiResponse(data: $data, message: $message, code: $code, additional: $additional, headers: $headers);
    }

    public static function apiResponse(mixed $data = null, bool $success = true, string $message = '', int $code = Response::HTTP_OK, array $additional = [], array $headers = []): \Illuminate\Http\JsonResponse
    {
        $response = [
                'success' => $success,
                'message' => $message,
                'data' => $data,
            ] + $additional;
        return response()->json($response, $code, $headers);
    }

    public static function errorResponse(mixed $data = null, string $message = '', int $code = Response::HTTP_UNPROCESSABLE_ENTITY, array $headers = []): \Illuminate\Http\JsonResponse
    {
        return self::apiResponse(success: false, message: $message, code: $code, additional: ['errors' => $data], headers: $headers);
    }

    public static function paginateResponse($data, $collection): \Illuminate\Http\JsonResponse
    {
        $meta = [
            'meta' => [
                'first_page' => $collection->firstItem(),
                'current_page' => $collection->currentPage(),
                'has_more_pages' => $collection->hasMorePages(),
                'per_page' => $collection->perPage(),
            ],
        ];

        return self::apiResponse(data: $data, additional: $meta);
    }

    public static function throwException($exception, $message, $code = Response::HTTP_UNPROCESSABLE_ENTITY)
    {
        throw new $exception(
            self::errorResponse(message: $message, code: $code)
        );
    }
}
