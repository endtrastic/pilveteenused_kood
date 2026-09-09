<?php

declare(strict_types=1);

namespace Laenutus;

use PDO;
use PDOException;

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $host = Config::get('MYSQLHOST', 'localhost');
        $port = Config::get('MYSQLPORT', '3306');
        $name = Config::get('MYSQLDATABASE', 'laenutus');
        $user = Config::get('MYSQLUSER', 'laenutus');
        $password = Config::get('MYSQLPASSWORD', '');

        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $name);

        try {
            self::$connection = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            error_log('[laenutus] Database connection failed: ' . $e->getMessage());
            throw $e;
        }

        return self::$connection;
    }
}
