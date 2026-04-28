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

    public static function all($search, $category, $userId)
    {
        $table = static::$table;
        $sql = "SELECT *, 
        (SELECT COUNT(*) FROM comments c WHERE c.postId = p.id) AS total_comments, 
        (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id ) AS total_likes, 
        (SELECT name FROM users WHERE id = p.userId) AS author_name,
        (SELECT img FROM users WHERE id = p.userId) AS author_img,
        EXISTS (SELECT * FROM likes l WHERE l.post_id = p.id AND l.user_id = :userId LIMIT 1) AS is_liked
        FROM $table p WHERE 1=1";

        $params = [];
        $params['userId'] = $userId;

        if (! empty($category)) {
            $sql .= ' AND category = :category';
            $params['category'] = $category;
        }

        if (! empty($search)) {
            $sql .= ' AND title LIKE :search';
            $params['search'] = "%$search%";
        }

        $stmt = self::db()->prepare($sql);

        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public static function find($id, $userId)
    {
        $table = static::$table;

        $stmt = self::db()->prepare("SELECT *, 
        (SELECT COUNT(*) FROM comments c WHERE c.postId = p.id) AS total_comments, 
        (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id) AS total_likes, 
        (SELECT name FROM users WHERE id = p.userId) AS author_name,
        (SELECT img FROM users WHERE id = p.userId) AS author_img,
        EXISTS (SELECT * FROM likes l WHERE l.post_id = :id AND l.user_id = :userId LIMIT 1) AS is_liked
        FROM $table p WHERE id = :id;");

        $stmt2 = self::db()->prepare('SELECT *,
        (SELECT name FROM users WHERE id = c.userId) AS author_name,
        (SELECT img FROM users WHERE id = c.userId) AS author_img
        FROM comments c WHERE postId = :id;');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':userId', $userId);
        $stmt2->bindParam(':id', $id);
        $stmt->execute();
        $stmt2->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $comments = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        return ['post' => $result, 'comments' => $comments];
    }

    public static function isPostLiked($postId, $userId)
    {
        $table = static::$table;

        $stmt = self::db()->prepare("SELECT * FROM $table WHERE post_id = :post_id and user_id = :user_id");
        $stmt->bindParam(':post_id', $postId);
        $stmt->bindParam(':user_id', $userId);
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

    public static function deleteCommentsAndLikeByPostId($id)
    {
        $db = self::db();
        $table = static::$table;

        try {
            $db->beginTransaction();

            $stmt1 = $db->prepare('DELETE FROM likes WHERE post_id = :id');
            $stmt1->execute(['id' => $id]);

            $stmt2 = $db->prepare('DELETE FROM comments WHERE postId = :id');
            $stmt2->execute(['id' => $id]);

            $stmt3 = $db->prepare("DELETE FROM $table WHERE id = :id");
            $stmt3->execute(['id' => $id]);

            $db->commit();

            return true;
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }
}
