<?php

namespace App\Models;

use App\Core\Database;
use App\Helpers\Response;
// use WpOrg\Requests\Response;
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

        $stmt = self::db()->prepare("SELECT * FROM $table");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public static function find($id)
    {
        $table = static::$table;

        $stmt = self::db()->prepare("SELECT * FROM $table WHERE id = :id");
        $stmt->bindParam(':id', $id);
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

        $sql = "UPDATE $table SET $fields WHERE post_id = :id";
        $data['id'] = $id;

        $stmt = self::db()->prepare($sql);

        $result = $stmt->execute($data);
        Response::json($result);
    }

    public static function delete($id)
    {
        $table = static::$table;

        $stmt = self::db()->prepare("DELETE FROM $table WHERE post_id = :id");

        return $stmt->execute(['id' => $id]);
    }
}
