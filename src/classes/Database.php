<?php

namespace Classes;
class Database {

    private function connect() {
        try {
            $type = config('database.type', 'mysql');
            $host = config('database.host', '127.0.0.1'); // "localhost" used for host in config ...
            $name = config('database.name', 'database');
            $username = config('database.username', 'admin'); // "root" used for password and username in config ...
            $password = config('database.password', 'admin');

            $db_string = '{$type}:host={$host};dbname={$name}';
            $db_connection = new PDO($db_string, $username, $password);
            return $db_connection;

        } catch (PDOException $e) {
            log($e->getMessage());

            die();
        }
    }
}