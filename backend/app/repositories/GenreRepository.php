<?php

namespace app\repositories;
use PDO;

class GenreRepository {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function get(): array
    {
        $sql = "SELECT * FROM genres";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    public function getId(string $name): int
    {
        $sql = "SELECT id FROM genres WHERE name = :name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['name'=>$name]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data["id"];
    }
    
    public function existByName($name) : bool
    {
        $sql = "SELECT * FROM genres WHERE name = :name";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['name'=>$name]);
        
        $result = $stmt->rowCount() > 0;

        return $result;
    }

    public function getGenresByMovieId($id): array
    {
        $sql = "SELECT GROUP_CONCAT(DISTINCT g.name SEPARATOR ', ') AS genres
                FROM genres g
                JOIN movies_genres ms ON ms.id_genre = g.id
                WHERE ms.id_movie = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id'=>$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }
}