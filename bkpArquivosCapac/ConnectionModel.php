<?php


class ConnectionModel
{
    public static $conn;

    public static function connection() {
        if(!isset(self::$conn)) {
            self::$conn = new PDO("mysql:host=localhost;dbname=capac;", "root", "", array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$conn;
    }
}