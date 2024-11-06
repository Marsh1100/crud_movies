<?php

namespace app\repositories;
use PDO;

//use app\interfaces\MovieInterface;

class MovieRepository {
    private $pdo;


    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $sql = "SELECT m.id, m.title, m.year, m.url, c.name AS country,  GROUP_CONCAT(DISTINCT g.name SEPARATOR ', ') AS genres,
                GROUP_CONCAT(DISTINCT CONCAT(
                    '{\"name\":\"', s.name, '\", ',
                    '\"director\":\"', ms.director, '\", ',
                    '\"protagonist\":\"', ms.protagonist, '\"}'
                ) SEPARATOR ', ') AS staff
                FROM movies m
                JOIN countries c ON m.id_country = c.id
                JOIN movies_genres mg ON  m.id = mg.id_movie
                JOIN movies_staff ms ON  m.id = ms.id_movie
                JOIN genres g ON mg.id_genre  = g.id
                JOIN staff s ON   ms.id_person = s.id
                GROUP BY m.id;";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public function getById($id)
    {
        $sql = "SELECT m.id, m.title, m.year, m.url, c.name AS country,  GROUP_CONCAT(DISTINCT g.name SEPARATOR ', ') AS genres,
                GROUP_CONCAT(DISTINCT CONCAT(
                    '{\"name\":\"', s.name, '\", ',
                    '\"director\":\"', ms.director, '\", ',
                    '\"protagonist\":\"', ms.protagonist, '\"}'
                ) SEPARATOR ', ') AS staff
                FROM movies m
                JOIN countries c ON m.id_country = c.id
                JOIN movies_genres mg ON  m.id = mg.id_movie
                JOIN movies_staff ms ON  m.id = ms.id_movie
                JOIN genres g ON mg.id_genre  = g.id
                JOIN staff s ON   ms.id_person = s.id
                WHERE m.id = :id;";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id'=>$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public function create($data):int
    {
        $sql = "INSERT INTO movies (title, year, id_country, url)"
        ."VALUES (:title, :year, :id_country, :url)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);  

        return  $this->pdo->lastInsertId();
    }
    public function update($data)
    {
        $sql = "UPDATE  movies "
        ."SET title = :title, year = :year, id_country = :id_country, url = :url "
        ."WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }
    public function delete($id)
    {
        $sql = "DELETE FROM movies "."WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id'=>$id]);
    }
    public function exists(int $id): bool
    {
        $sql = "SELECT * FROM movies WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id'=>$id]);
        
        $result = $stmt->rowCount() > 0;

        return $result;
    }
    
    public function existByName($title) : bool
    {
        $sql = "SELECT * FROM movies WHERE title = :title";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['title'=>$title]);
        
        $result = $stmt->rowCount() > 0;

        return $result;
    }
}