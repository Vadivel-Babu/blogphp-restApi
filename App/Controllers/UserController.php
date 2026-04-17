<?php

namespace App\Controllers;

use App\Helpers\Response;
use App\Helpers\Validate;
use App\Models\User;
use App\Services\JwtService;

class UserController
{
    public function index()
    {
        // $users = User::all();
        Response::json([]);
    }

    // public function show($id)
    // {
    //     $user = User::find($id);
    //     Response::json($user);
    // }

    public function register()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $isValidate = Validate::validation($data, 'register');
        if (! $isValidate['status']) {
            Response::json($isValidate, 400);
        } else {
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
            $data['password'] = $password;
            $user = User::findByEmail($data['email']);
            if (! $user) {
                $newUser = User::create($data);
                if ($newUser) {
                    Response::json(['message' => 'register succesfully'], 201);
                }
            } else {
                Response::json(['message' => 'user email already existed'], 400);
            }
        }
    }

    public function login()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $isValidate = Validate::validation($data, 'login');
        if (! $isValidate['status']) {
            Response::json($isValidate, 400);
        } else {
            $user = User::findByEmail($data['email']);

            if (! $user) {
                Response::json(['message' => 'user not found'], 404);
            } else {
                if (password_verify($data['password'], $user['password'])) {
                    $token = JwtService::generate($user);

                    Response::json([
                        'message' => 'Login successful',
                        'token' => $token
                    ]);
                } else {
                    Response::json(['message' => 'invalid password'], 401);
                }
            }
        }
    }

    public function update()
    {
        // $data = file_get_contents('php://input');
        $name = $_POST['name'];
        $email = $_POST['email'];
        $pass = $_POST['password'] ?? '';
        $newPass = $_POST['newpassword'] ?? '';
        $img = $_FILES['img'] ?? null;
        $user = User::findByEmail($email);
        if ($user) {
            if ($img) {
                $isValidate = Validate::validation($img, 'img');
                if ($isValidate['status']) {
                    $img = $isValidate['img'];
                } else {
                    Response::json([$isValidate['message']]);
                }
            } else {
                Response::json(['img not there'], 400);
            }
            if (password_verify($pass, $user['password'])) {
                $newPass = password_hash($newPass, PASSWORD_DEFAULT);
            } else {
                Response::json(['message' => 'invalid password'], 400);
            }
            $updatedData = ['name' => $name, 'email' => $email, 'password' => $newPass, 'img' => $img];
            $result = User::update($user['id'], $updatedData);
            if ($result) {
                Response::json(['message' => 'user updated successfully'], 204);
            } else {
                Response::json(['message' => 'user not updated'], 400);
            }
        } else {
            Response::json(['message' => 'user not found'], 404);
        }
    }

    public function logout()
    {
        Response::json(['message' => 'auth controller logout']);
    }
}
