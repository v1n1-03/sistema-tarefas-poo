<?php
class Database {
    private static ?PDO $connection = null;
    public static function getConnection(): PDO {
        if (self::$connection === null) {
            self::$connection = new PDO(
                'mysql:host=db;dbname=tarefas;charset=utf8mb4',
                'root',
                'root'
            );
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        return self::$connection;
    }
}
