<?php

namespace App\Controllers;

use App\Helpers\Response;
use App\Models\Like;

class LikeController
{
    public function index()
    {
        $data = Like::all();
        Response::json($data);
    }

    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? null;

        if ($id) {
            $isLiked = Like::find($id);
            if (! $isLiked) {
                $result = Like::create($data);
                Response::json(['message' => 'post liked', 'data' => $result]);
            } else {
                $id = $data['id'];
                $newData = array_diff_key($data, array('id' => true));
                $result = Like::update($id, $newData);
                Response::json($result);
            }
        }
    }
}
