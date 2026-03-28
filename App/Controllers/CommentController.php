<?php

namespace App\Controllers;

use App\Helpers\Response;
use App\Models\Comment;

class CommentController
{
    public function index()
    {
        $data = Comment::all();
        Response::json(['message' => 'get all comments', 'data' => $data]);
    }

    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = Comment::create($data);
        Response::json(['message' => 'created', 'data' => $result]);
    }

    public function update($id)
    {
        $id = $id + 0;
        $data = json_decode(file_get_contents('php://input'), true);
        $result = Comment::update($id, $data);
        Response::json(['message' => 'updated', 'id' => $id, 'result' => $result]);
    }

    public function delete($id)
    {
        $result = Comment::delete($id);
        Response::json(['message' => 'deleted', 'id' => $result]);
    }
}
