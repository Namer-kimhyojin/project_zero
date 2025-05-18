<?php
namespace App\Lib;

require_once __DIR__ . '/../config/config.php';
use PDO;

class Database {
  private static $connection;

  public static function getConnection(): PDO {
    if (!self::$connection) {
      $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
      self::$connection = new PDO($dsn, DB_USER, DB_PASSWORD, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
      ]);
    }
    return self::$connection;
  }
}
