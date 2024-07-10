<?php

namespace App\Database;

use PDO;
use PDOException;

class Database
{
    protected static $pdo;

    public function __construct(array $config)
    {
        try {
            self::$pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4", $config['user'], $config['pass']);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            $this->renderErrorView($e->getMessage(), $config['user']);
        }
    }

    public static function conn()
    {
        return self::$pdo;
    }

    private function renderErrorView(string $message, string $username): void
    {
        require BASE_PATH . '/errors/database-connection.php';
        exit();
    }
}
