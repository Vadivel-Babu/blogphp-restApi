<?php

namespace App\Controllers;

use App\Helpers\Response;
use App\Models\Comment;

class CommentController
{
    public function index()
    {
        $data = Comment::all();
        Response::json(['message' => 'get all comments']);
    }

    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);
    }
}
