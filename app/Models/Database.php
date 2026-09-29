<?php

declare(strict_types=1);

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(array $config): ?PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $dbConfig = $config['db'] ?? [];

        if ($dbConfig === []) {
            return null;
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $dbConfig['host'] ?? 'localhost',
            $dbConfig['port'] ?? '3306',
            $dbConfig['database'] ?? '',
            $dbConfig['charset'] ?? 'utf8mb4'
        );

        try {
            self::$connection = new PDO(
                $dsn,
                $dbConfig['username'] ?? 'root',
                $dbConfig['password'] ?? '',
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException) {
            return null;
        }

        return self::$connection;
    }
}
