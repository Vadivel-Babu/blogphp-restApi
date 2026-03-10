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
    }
}
