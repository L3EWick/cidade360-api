<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['success' => false, 'message' => 'Usuário não encontrado'], Response::HTTP_UNAUTHORIZED);
            }
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['success' => false, 'message' => 'Token inválido'], Response::HTTP_UNAUTHORIZED);
            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['success' => false, 'message' => 'Token expirado'], Response::HTTP_UNAUTHORIZED);
            } else {
                return response()->json(['success' => false, 'message' => 'Token de autorização não encontrado'], Response::HTTP_UNAUTHORIZED);
            }
        }

        return $next($request);
    }
}