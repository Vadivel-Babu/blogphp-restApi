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
            $data = ['post_id' => $postId, 'user_id' => $userId];
            $result = Like::create($data);
            Response::json(['message' => 'post liked'], 201);
        } else if ($isLiked) {
            $id = $isLiked['id'];
            $result = Like::delete($id);
            Response::json(['message' => 'post disliked'], 204);
        } else {
            Response::json(['message' => 'somthing went wrong'], 400);
        }
    }
}
