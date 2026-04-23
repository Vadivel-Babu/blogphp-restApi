<?php

namespace App\Controllers;

use App\Helpers\Response;
use App\Helpers\Validate;
use App\Models\Post;

class PostController
{
    public function index()
    {
        $user = $_REQUEST['user'] ?? null;
        $userId = $user->user_id ?? null;
        $category = $_GET['category'] ?? '';
        $search = $_GET['search'] ?? '';
        $posts = Post::all($search, $category, $userId);
        Response::json($posts);
    }

    public function show($id)
    {
        $id = $id + 0;
        $user = $_REQUEST['user'] ?? null;
        $userId = $user->user_id ?? null;
        $post = Post::find($id, $userId);
        Response::json($post);
    }

    public function store()
    {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $category = $_POST['category'];
        $img = $_FILES['img'] ?? null;
        $userId = $_POST['userId'];
        $data = ['title' => $title, 'content' => $content, 'category' => $category, 'userId' => $userId, 'img' => $img];
        if ($img) {
            $isValidate = Validate::validation($img, 'img');
            if ($isValidate['status']) {
                $data['img'] = $isValidate['img'];
                $post = Post::create($data);
                if ($post) {
                    Response::json(['message' => 'post updated successfully'], 201);
                } else {
                    Response::json(['message' => 'failed post updated'], 400);
                }
            } else {
                Response::json(['message' => $isValidate['message']], 400);
            }
        } else {
            $post = Post::create($data);
            Response::json($post, 201);
        }
    }

    public function update($id)
    {
        echo $id;
        $id = $id + 0;
        // Response::json($id);
        // $data = json_decode(file_get_contents('php://input'), true);
        $title = $_POST['title'];
        $content = $_POST['content'];
        $category = $_POST['category'];
        $img = $_FILES['img'] ?? null;
        $userId = $_POST['userId'];
        $data = ['title' => $title, 'content' => $content, 'category' => $category, 'userId' => $userId, 'img' => $img];
        if ($img) {
            $isValidate = Validate::validation($img, 'img');
            if ($isValidate['status']) {
                $data['img'] = $isValidate['img'];
                $update = Post::update($id, $data);
                if ($update) {
                    Response::json(['message' => 'post updated successfully'], 201);
                } else {
                    Response::json(['message' => 'failed post updated'], 400);
                }
            } else {
                Response::json(['message' => $isValidate['message']], 400);
            }
        } else {
            if (! $title || ! $content || ! $category || ! $userId) {
                Response::json(['message' => 'failed to update the post'], 400);
            }
            $update = Post::update($id, $data);
            if ($update) {
                Response::json(['message' => 'post updated successfully'], 201);
            } else {
                Response::json(['message' => 'failed to update the post'], 400);
            }
        }
    }

    public function delete($id)
    {
        $id = $id + 0;
        $deleted = Post::delete($id);
        Response::json($deleted);
    }
}
