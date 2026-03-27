<?php

namespace App\Controllers;

use App\Helpers\Response;

class LikeController
{
    public function handleLike()
    {
        Response::json(['message' => 'liked succesfully']);
    }
}
