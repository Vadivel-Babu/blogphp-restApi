<?php

namespace App\Controllers;

use App\Helpers\Response;

class PostController
{
    public function getAllPosts()
    {
        Response::json(['message' => 'all post fetched']);
    }
}
