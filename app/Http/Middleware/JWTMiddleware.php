<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parsetoken()->authenticate();

            if (!$user) {
                throw new Exception('Usuário não encontrado', Response::HTTP_NOT_FOUND);
            }
        } catch (Throwable $th) {
            if ($th instanceof TokenExpiredException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token Expirou.',
                ], Response::HTTP_UNAUTHORIZED);

            } else if ($th instanceof TokenInvalidException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token inválido',
                ], Response::HTTP_UNAUTHORIZED);

            } else if ($th instanceof TokenBlacklistedException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token banido.',
                ], Response::HTTP_UNAUTHORIZED);
            }
        }

        return $next($request);
    }
}
