<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Model
{
    protected static $table;

    protected static function db()
    {
        return Database::connect();
    }

    public static function all()
    {
        $table = static::$table;

        $stmt = self::db()->prepare("SELECT *, 
        (SELECT COUNT(*) FROM comments c WHERE c.postId = p.id) AS total_comments, 
        (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id AND l.isLiked = 1) AS total_likes, 
        (SELECT name FROM users WHERE id = p.userId) AS author_name,
        (SELECT img FROM users WHERE id = p.userId) AS author_img,
        (SELECT l.isLiked FROM likes l WHERE l.post_id = p.id AND l.user_id = 2 LIMIT 1) AS is_liked FROM $table p;");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public static function find($id)
    {
        $table = static::$table;

        $stmt = self::db()->prepare("SELECT *, 
        (SELECT COUNT(*) FROM comments c WHERE c.postId = p.id) AS total_comments, 
        (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id AND l.isLiked = 1) AS total_likes, 
        (SELECT name FROM users WHERE id = p.userId) AS author_name,
        (SELECT img FROM users WHERE id = p.userId) AS author_img,
        (SELECT l.isLiked FROM likes l WHERE l.post_id = p.id AND l.user_id = 2 LIMIT 1) AS is_liked FROM $table p WHERE id = :id;");
        $stmt2 = self::db()->prepare('SELECT c.id, c.content,  
        (SELECT name FROM users WHERE id = c.userId) AS author_name,
        (SELECT img FROM users WHERE id = c.userId) AS author_img  
        FROM comments c WHERE postId = :id;');
        $stmt->bindParam(':id', $id);
        $stmt2->bindParam(':id', $id);
        $stmt->execute();
        $stmt2->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $comments = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        return ['post' => $result, 'comments' => $comments];
    }

    public static function findById($id)
    {
        $table = static::$table;

        $stmt = self::db()->prepare("SELECT * FROM $table WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public static function findByEmail($email)
    {
        $table = static::$table;

        $stmt = self::db()->prepare("SELECT * FROM $table WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public static function create($data)
    {
        $table = static::$table;
        $columns = implode(',', array_keys($data));
        $placeholders = ':'.implode(',:', array_keys($data));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

        $stmt = self::db()->prepare($sql);

        return $stmt->execute($data);
    }

    public static function update($id, $data)
    {
        $table = static::$table;

        $fields = '';
        // $currData = [];

        foreach ($data as $key => $value) {
            $fields .= "$key = :$key,";
        }
        $fields = rtrim($fields, ',');

        $sql = "UPDATE $table SET $fields WHERE id = :id";
        $data['id'] = $id;

        $stmt = self::db()->prepare($sql);

        $result = $stmt->execute($data);

        return $result;
    }

    public static function delete($id)
    {
        $table = static::$table;

        $stmt = self::db()->prepare("DELETE FROM $table WHERE id = :id");

        return $stmt->execute(['id' => $id]);
    }
}
