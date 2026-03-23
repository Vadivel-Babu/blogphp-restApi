<?php

namespace App\Helpers;

class Validate
{
    public static function validation($data, $authType)
    {
        if ($authType === 'register') {
            $name = $data['name'];
            $email = $data['email'];
            $password = $data['password'];

            if ($name === '' || $email === '' || $password === '') {
                return ['status' => false, 'message' => 'all fields are required'];
            }
            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['status' => false, 'message' => 'Invalid email formate'];
            }

            return ['status' => true];
        } elseif ($authType === 'login') {
            $email = $data['email'];
            $password = $data['password'];

            if ($email === '' || $password === '') {
                return ['status' => false, 'message' => 'all fields are required'];
            }
            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['status' => false, 'message' => 'Invalid email formate'];
            }

            return ['status' => true];
        }
    }
}
