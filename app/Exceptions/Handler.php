<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
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

        $this->renderable(function (RouteNotFoundException $e, $request) {
            // Maneja el error de ruta no encontrada
            if ($request->is('api/*')) {
                return response()->json(['message' => 'Route not found'], 404);
            }
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        // Manejo de errores de validación
        if ($exception instanceof ValidationException) {
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos.',
                'errors' => $exception->errors(),
            ], 422);
        }

        // Manejo de errores de autenticación
        if ($exception instanceof AuthenticationException) {
            return response()->json(['message' => 'No autenticado.'], 401);
        }

        // Puedes agregar otros manejos personalizados aquí...

        return parent::render($request, $exception);
    }
}
