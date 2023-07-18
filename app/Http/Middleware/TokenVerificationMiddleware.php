<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized',
            ]);
        }
        $userEmail = JWTToken::decodeToken($token);
        if (!$userEmail) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized',
            ]);
        }
        $request->headers->set('userEmail', $userEmail);
        return $next($request);
    }
}
