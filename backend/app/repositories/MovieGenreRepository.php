<?php

namespace app\repositories;
use PDO;

class MovieGenreRepository {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function create($id_movie, $id_genre)
    {
        $sql = "INSERT INTO movies_genres (id_movie, id_genre)"
        ." VALUES (:id_movie, :id_genre)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([ 'id_movie'=>$id_movie,'id_genre'=> $id_genre ]);
    }

    public function delete($id_movie, $name)
    {
        $sql = "DELETE FROM movies_genres"
        ." WHERE id_movie = :id_movie AND id_genre = (SELECT id FROM genres WHERE genres.name = :name)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([ 'id_movie'=>$id_movie,'name'=> $name ]);
    }
    

    //INSERT INTO dbmovies.movies_genres (id_movie, id_genre) VALUES (1,1);
}