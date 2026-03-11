<?php

namespace App\Controllers;

use App\Helpers\Response;
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

    // public function store()
    // {
    //     $data = json_decode(file_get_contents('php://input'), true);

    //     $id = User::create($data);

    //     Response::json(['id' => $id]);
    // }

    // public function destroy($id)
    // {
    //     User::delete($id);

    //     Response::json(['message' => 'Deleted']);
    // }
}
