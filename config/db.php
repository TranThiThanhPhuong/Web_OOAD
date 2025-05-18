<?php
class Database {
    private static $conn = null;

    public static function connect() {
        if (self::$conn === null) {
            $host = 'localhost';
            $dbname = 'calendar_db';
            $username = 'root';
            $password = '';

            try {
                self::$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }

        return self::$conn;
    }
}
?>
