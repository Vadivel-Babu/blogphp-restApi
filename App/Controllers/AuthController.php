<?php

use App\Helpers\Response;

class AuthController
{
    public function register()
    {
        Response::json(['message' => 'auth controller register']);
    }

    public function login()
    {
        Response::json(['message' => 'auth controller login']);
    }

    public function logout()
    {
        Response::json(['message' => 'auth controller logout']);
    }
}
