<?php

namespace app\repositories;
use PDO;

//use app\interfaces\CountryInterface;

class CountryRepository {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function get(): array
    {
        
        $sql = "SELECT name FROM countries";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }


    public function getIdCountryByName($name) : int
    {
        $sql = "SELECT IFNULL((SELECT id FROM countries WHERE name = :name) ,0 ) as idCountry";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['name'=>$name]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data["idCountry"];
    }
    
}