<?php

use App\Helpers\Response;

class AuthController
{
    public function register()
    {
        Response::json(['message' => 'auth controller']);
    }

    public function login()
    {
        Response::json(['message' => 'auth controller']);
    }
}
