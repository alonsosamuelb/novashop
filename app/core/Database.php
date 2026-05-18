<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $config = require CONFIG_PATH . '/database.php';

        $dsn = sprintf(
            '%s:host=%s;port=%d;dbname=%s;charset=%s',
            $config['driver'],
            $config['host'],
            (int) $config['port'],
            $config['database'],
            $config['charset']
        );

        try {
            self::$connection = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                $config['options']
            );
        } catch (PDOException $exception) {
            http_response_code(500);

            if ((bool) config('app.debug', false)) {
                exit('Error de conexion con la base de datos: ' . e($exception->getMessage()));
            }

            exit('Error interno de conexion con la base de datos.');
        }

        return self::$connection;
    }
}
