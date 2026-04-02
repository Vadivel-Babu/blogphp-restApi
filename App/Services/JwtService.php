<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    private static $secret = 'this-is-my-secret-key-for-my-php-blog-api';

    public static function generate($user)
    {
        $payload = [
            'iss' => 'blog-api',
            'iat' => time(),
            'exp' => time() + 3600,
            'user_id' => $user['id'],
            'email' => $user['email']
        ];

        return JWT::encode($payload, self::$secret, 'HS256');
    }

    public static function verify($token)
    {
        try {
            return JWT::decode($token, new Key(self::$secret, 'HS256'));
        } catch (\Exception $e) {
            return false;
        }
    }
}
