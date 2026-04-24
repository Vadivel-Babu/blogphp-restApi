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
        if ($result) {
            Response::json(['message' => 'commented successfully'], 201);
        } else {
            Response::json(['message' => 'something went wrong'], 400);
        }
    }

    public function update($id)
    {
        $id = $id + 0;
        $data = json_decode(file_get_contents('php://input'), true);
        $result = Comment::update($id, $data);
        if ($result) {
            Response::json(['message' => 'comment updated successfully'], 204);
        } else {
            Response::json(['message' => 'something went wrong'], 400);
        }
    }

    public function delete($id)
    {
        $result = Comment::delete($id);
        if ($result) {
            Response::json(['message' => 'comment deleted successfully'], 204);
        } else {
            Response::json(['message' => 'something went wrong'], 400);
        }
    }
}
