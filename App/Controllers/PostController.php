<?php

namespace App\Controllers;

use App\Helpers\Response;
use App\Models\Post;

class PostController
{
    public function index()
    {
        $posts = Post::all();
        Response::json($posts);
    }

    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $post = Post::create($data);
        Response::json($post, 201);
    }

    public function update($id)
    {
        $id = $id + 0;
        // Response::json($id);
        $data = json_decode(file_get_contents('php://input'), true);
        $update = Post::update($id, $data);
        Response::json($update);
    }

    public function delete($id)
    {
        $id = $id + 0;
        $deleted = Post::delete($id);
        Response::json($deleted);
    }
}
