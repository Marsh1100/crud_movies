<?php

namespace app\repositories;
use PDO;

class StaffRepository {
    private $pdo;
    
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function get(int | null $id): array
    {
        $sql = "SELECT * FROM staff";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    public function create(string $data):int
    {
        $sql = "INSERT INTO staff (name)"
        ."VALUES (:name)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['name'=>$data]);

        return $this->pdo->lastInsertId();
    }
    public function getId(string $name): int
    {
        $sql = "SELECT id FROM staff WHERE name = :name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['name'=>$name]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data["id"];
    }

    public function existByName($name) : bool
    {
        $sql = "SELECT * FROM staff WHERE name = :name";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['name'=>$name]);
        
        $result = $stmt->rowCount() > 0;

        return $result;
    }
}