<?php

namespace App\Controllers;

use App\Helpers\Response;
use App\Helpers\Validate;
use App\Models\User;

class UserController
{
    public function index()
    {
        $users = User::all();
        Response::json($users);
    }

    public function show($id)
    {
        $user = User::find($id);
        Response::json($user);
    }

    public function register()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $isValidate = Validate::validation($data, 'register');
        if (! $isValidate['status']) {
            Response::json($isValidate);
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
            Response::json($isValidate);
        } else {
            $user = User::findByEmail($data['email']);

            if (! $user) {
                Response::json(['message' => 'user not found'], 404);
            } else {
                if (password_verify($data['password'], $user['password'])) {
                    Response::json(['message' => 'logged in succesfully']);
                } else {
                    Response::json(['message' => 'invalid password'], 401);
                }
            }
        }
    }

    public function update()
    {
        $data = file_get_contents('php://input');
        $name = $_POST['name'];
        $pass = $_POST['password'] ?? null;
        $img = $_FILES['img'] ?? null;
        // $data = ['name' => $name, '$pass' => "$pass", 'img' => $img];
        if ($img) {
            $isValidate = Validate::validation($img, 'img');
            if ($isValidate['status']) {
                Response::json(['data' => $isValidate['img']]);
            } else {
                Response::json($isValidate);
            }
        } else {
            Response::json(['img not there'], 200);
        }
    }

    public function logout()
    {
        Response::json(['message' => 'auth controller logout']);
    }
}
