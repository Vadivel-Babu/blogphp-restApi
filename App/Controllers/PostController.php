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
}
