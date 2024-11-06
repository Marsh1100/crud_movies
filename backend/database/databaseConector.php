<?php

namespace database;

use PDO;
use PDOException;

class DatabaseConnection {
    private $pdo;

    public function __construct() {
        try {

            $dsn = "mysql:host=". DB_HOST .";dbname=". DB_NAME .";charset=utf8";
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS);
    
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}
