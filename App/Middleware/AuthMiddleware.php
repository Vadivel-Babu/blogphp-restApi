<?php

namespace App\middleware;

use App\Helpers\Response;

class AuthMiddleware
{
    public function auth()
    {
        $token = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

        if (! $token) {
            Response::json([
                'message' => 'Unauthorized'
            ], 401);
            exit;
        }

        $token = explode(' ', $token)[1];

        if ($token !== '12345') {
            Response::json([
                'message' => 'Invalid token'
            ], 401);
        }
    }
}
