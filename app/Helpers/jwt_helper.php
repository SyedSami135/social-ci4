<?php

namespace App\Helpers;

use Firebase\JWT\JWT;



function createJWT($userId,  $expirationTime = 3600): string
{
    $issuedAt = time();
    $secretKey = env('JWT_SECRET_KEY');
    $payload = [
        'iat' => $issuedAt,
        'exp' => $issuedAt + $expirationTime,
        'sub' => $userId,
    ];
    try {
        $jwt = JWT::encode($payload, $secretKey, 'HS256');
        return  $jwt;
    } catch (\Exception $e) {
        return null;
    }
}

function verifyJWT($jwt)
{
    $secretKey = env('JWT_SECRET_KEY');
    try {
        return JWT::decode($jwt, $secretKey);
    } catch (\Exception $e) {
        return null;
    }
}
