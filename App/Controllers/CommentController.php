<?php

namespace App\Controllers;

use App\Helpers\Response;
use App\Models\Comment;

class CommentController
{
    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = Comment::create($data);
        Response::json(['message' => 'created'], 201);
    }

    public function update($id)
    {
        $id = $id + 0;
        $data = json_decode(file_get_contents('php://input'), true);
        $result = Comment::update($id, $data);
        Response::json(['message' => 'updated'], 204);
    }

    public function delete($id)
    {
        $result = Comment::delete($id);
        Response::json(['message' => 'deleted'], 204);
    }
}
