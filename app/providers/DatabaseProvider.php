<?php

namespace App\Providers;

class DatabaseProvider
{
    public static function connect()
    {
        static $connection;

        if ($connection instanceof \mysqli) {
            return $connection;
        }

        $config = require __DIR__ . '/../config.php';
        $db = $config['database'];

        $connection = new \mysqli($db['host'], $db['user'], $db['password'], $db['name']);

        if ($connection->connect_error) {
            die('Erro ao conectar ao banco de dados: ' . $connection->connect_error);
        }

        $connection->set_charset('utf8mb4');

        return $connection;
    }
}
