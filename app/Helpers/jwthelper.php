<?php

namespace App\Helpers;

use Firebase\JWT\JWT;



function createJWT($userId, $secretKey, $expirationTime = 3600) {
    $issuedAt = time();
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

function verifyJWT($jwt, $secretKey) {
    try {
        return JWT::decode($jwt, $secretKey);
    } catch (\Exception $e) {
        return null;
    }
}
