<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (Throwable $e) {
            return $this->apiErrorResponse($e);
        });
    }

    /**
     * エラーコードごとにエラーを返却する処理
     *
     * @param [type] $exception
     * @return void
     */
    private function apiErrorResponse($exception)
    {
        if ($this->isHttpException($exception)) {
            $statusCode = $exception->getStatusCode();

            switch ($statusCode) {
                case 400:
                    return response()->json([
                        'message' => trans('error.bad_request')
                    ], 400);
                case 401:
                        return response()->json([
                        'message' => trans('error.unauthorized')
                    ], 401);
                case 403:
                    return response()->json([
                        'message' => trans('error.forbidden')
                    ], 403);
                case 404:
                    return response()->json([
                        'message' => __('error.not_found')
                    ], 404);
                case 422:
                    return response()->json([
                        'message' => $exception->getMessage()
                    ], 422);
                case 500:
                    return response()->json([
                        'message' => trans('error.server')
                    ], 500);
            }
        } elseif ($exception instanceof ValidationException)
        {
            return response()->json([
                'message' => $exception->getMessage()
            ], 422);
        }
    }
}
