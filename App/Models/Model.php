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

        $stmt = self::db()->prepare("SELECT * FROM $table");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $table = static::$table;

        $stmt = self::db()->prepare("SELECT * FROM $table WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
