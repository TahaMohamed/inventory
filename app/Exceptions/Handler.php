<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

    }

    public function render($request, $exception)
    {
        if ($request->expectsJson()) {
            return match (true) {
                $exception instanceof HttpException && $exception->getStatusCode() <= Response::HTTP_INTERNAL_SERVER_ERROR =>  ApiResponse::apiResponse(success: false, message: $exception->getMessage(), code: $exception->getStatusCode()),
                $exception instanceof AuthenticationException =>  ApiResponse::apiResponse(success: false, message: __('Unauthenticated'), code: Response::HTTP_UNAUTHORIZED),
                $exception instanceof ModelNotFoundException ||
                $exception instanceof NotFoundHttpException  =>  ApiResponse::apiResponse(success: false, message: __('No data available.'), code: Response::HTTP_NOT_FOUND),
                $exception instanceof ValidationException =>  $this->invalidJson($request, $exception),
                default => ApiResponse::apiResponse(success: false, message: $exception->getMessage() . " in " . $exception->getFile() . " at line " . $exception->getLine(), code: Response::HTTP_INTERNAL_SERVER_ERROR)
            };
        }

        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        return $this->shouldReturnJson($request, $exception)
            ? ApiResponse::errorResponse(message: $exception->getMessage(), code: Response::HTTP_UNAUTHORIZED)
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }

    protected function invalidJson($request, ValidationException $exception): \Illuminate\Http\JsonResponse
    {
        return ApiResponse::errorResponse(data: $exception->validator?->errors()?->toArray(), message: $exception->validator?->messages()->first(), code: $exception->status);
    }
}
