<?php

namespace app\repositories;
use PDO;

class MovieStaffRepository {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function create($data)
    {
        $sql = "INSERT INTO movies_staff (id_movie, id_person, protagonist, director)"
        ." VALUES (:id_movie, :id_person, :protagonist, :director)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }
    }