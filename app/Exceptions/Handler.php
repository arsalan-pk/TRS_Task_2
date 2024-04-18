<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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

    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {
            if ($exception instanceof MethodNotAllowedHttpException) {
                return response()->json(['error' => 'Method Not Allowed'], 405);
            }

            if ($exception instanceof NotFoundHttpException) {
                return response()->json(['error' => 'Api Not Found.'], 404);
            }

            if ($exception instanceof UnauthorizedHttpException) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            if ($exception instanceof RouteNotFoundException) {
                return response()->json(['error' => 'Route not found'], 404);
            }


            if ($exception instanceof AuthenticationException) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

            if ($exception instanceof AuthorizationException) {
                return response()->json(['error' => 'Forbidden unauthorized'], 403);
            }
        }

        return parent::render($request, $exception);
    }
}
