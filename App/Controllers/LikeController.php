<?php

namespace App\Controllers;

use App\Helpers\Response;
use App\Models\Like;

class LikeController
{
    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $postId = $data['postId'];
        $userId = $data['userId'];

        $isLiked = Like::isPostLiked($postId, $userId);
        if (! $isLiked) {
            $result = Like::create($data);
            Response::json(['message' => 'post liked'], 201);
        } else {
            $id = $isLiked['id'];
            $result = Like::delete($id);
            Response::json(['message' => 'post disliked'], 204);
        }
    }
}
