<?php
// DB Handler
class DBHandler {
    public static $db;
    
    public static function initDB() {
        self::$db = new DB(HOST, DB, USER, PW);
    }
    
    public static function getDB() {
        return self::$db;
    }
}

?>