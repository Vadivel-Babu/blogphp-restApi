<?php

namespace App\middleware;

use App\Helpers\Response;
use App\Services\JwtService;

class AuthMiddleware
{
    public function auth()
    {
        $auth = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

        if (! $auth) {
            Response::json([
                'message' => 'Unauthorized'
            ], 401);
            exit;
        }

        $token = str_replace('Bearer ', '', $auth);

        $decoded = JwtService::verify($token);

        if (! $decoded) {
            Response::json(['message' => 'Invalid or expired token'], 401);
        }

        $_REQUEST['user'] = $decoded;
    }
}
