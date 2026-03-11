<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $host = 'localhost';
        $db = 'blog';
        $user = 'root';
        $pass = '';

        try {
            $this->connection = new PDO(
                "mysql:host=$host;dbname=$db;charset=utf8",
                $user,
                $pass
            );

            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        } catch (PDOException $e) {
            throw new \Exception('Database connection failed');
        }
    }

    public static function connect()
    {
        if (! self::$instance) {
            self::$instance = new Database();
        }

        return self::$instance->connection;
    }
}
