<?php

namespace App\Helper;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken
{
    public static function generateToken($user): string
    {
        $payload = [
            'iss' => "laravel-jwt", // Issuer of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60 * 60 * 24, // Expiration time
            'userEmail' => $user->email, // User email id
        ];
        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }

    public static function decodeToken($token)
    {
        try {
            $decode = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            return $decode->userEmail;
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized',
            ]);
        }
    }
}
