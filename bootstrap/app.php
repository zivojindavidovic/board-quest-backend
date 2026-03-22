<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn (Request $request) => $request->expectsJson() ? null : '/');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $e) {
            return response()->json([
                'success'    => false,
                'message'    => 'Unauthenticated.',
                'error_code' => 'UNAUTHENTICATED',
            ], 401);
        });

        $exceptions->render(function (ValidationException $e) {
            return response()->json([
                'success'    => false,
                'message'    => 'Validation failed.',
                'error_code' => 'VALIDATION_ERROR',
                'errors'     => $e->errors(),
            ], 422);
        });
    })->create();
