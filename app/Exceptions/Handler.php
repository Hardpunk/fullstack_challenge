<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof UnauthorizedHttpException) {
            $preException = $e->getPrevious();

            if ($preException instanceof TokenExpiredException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token Expirou.',
                ], Response::HTTP_UNAUTHORIZED);

            } else if ($preException instanceof TokenInvalidException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token inválido',
                ], Response::HTTP_UNAUTHORIZED);

            } else if ($preException instanceof TokenBlacklistedException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token banido.',
                ], Response::HTTP_UNAUTHORIZED);
            }
            if ($e->getMessage() === 'Token not provided') {
                return response()->json([
                    'success' => false,
                    'message' => 'Token não fornecido.',
                ], Response::HTTP_UNAUTHORIZED);
            } else if ($e->getMessage() === 'User not found') {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não encontrado',
                ], Response::HTTP_NOT_FOUND);
            }
        }
        return parent::render($request, $e);
    }
}
