<?php

namespace app\services;

use PDO;
use PDOException;
use Exception;


use app\exceptions\DataException;
use app\interfaces\MovieInterface;
use app\repositories\CountryRepository;
use app\repositories\GenreRepository;
use app\repositories\MovieGenreRepository;
use app\repositories\MovieRepository;
use app\repositories\MovieStaffRepository;
use app\repositories\StaffRepository;

class MovieService implements MovieInterface 
{
    private MovieRepository $movieRepository;
    private GenreRepository $genreRepository;
    private StaffRepository $staffRepository;
    private CountryRepository $countryRepository;
    private MovieGenreRepository $movieGenreRepository;
    private MovieStaffRepository $movieStaffRepository;
    private PDO $pdo;

    public function __construct(MovieRepository $movieRepository, GenreRepository $genreRepository, StaffRepository $staffRepository, CountryRepository $countryRepository, MovieGenreRepository $movieGenreRepository,  MovieStaffRepository $movieStaffRepository, PDO $pdo)
    {        
        $this->movieRepository = $movieRepository;
        $this->genreRepository = $genreRepository;
        $this->staffRepository = $staffRepository;
        $this->countryRepository = $countryRepository;
        $this->movieGenreRepository = $movieGenreRepository;
        $this->movieStaffRepository = $movieStaffRepository;
        $this->pdo = $pdo;
    }

    public function create($data)
    {
        try {
            $this->pdo->beginTransaction();
            $idCountry = $this->countryRepository->getIdCountryByName($data["country"]);

            if($this->movieRepository->existByName($data["title"])){
                $v = $data["title"];
                throw new DataException("La película $v ya existe"); 
            }else if($idCountry === 0)
            {
                $v = $data["country"];
                throw new DataException("El país $v no existe"); 
            }

            $newMovie = [
                "title" => $data["title"],
                "id_country" => $idCountry,
                "year" => $data["year"],
                "url" => $data["url"]
            ];
            $idMovie = $this->movieRepository->create($newMovie);

            foreach($data["genres"] as $genre)
            {
                $idGenre = 0;
                if(!$this->genreRepository->existByName($genre)){
                    throw new DataException("No existe el genero $genre"); 
                }else
                {
                    $idGenre =  $this->genreRepository->getId($genre);
                }

                $this->movieGenreRepository->create($idMovie,$idGenre);
            }

            foreach($data["staff"] as $person)
            {
                $idPerson = 0;
                if(!$this->staffRepository->existByName($person["name"])){
                
                    $idPerson = $this->staffRepository->create($person["name"]);  
                }else{
                    $idPerson =  $this->staffRepository->getId($person["name"]);
                }

                $newMovieStaff = [
                    "id_movie" => $idMovie,
                    "id_person" => $idPerson,
                    "protagonist" => $person["protagonist"],
                    "director" => $person["director"]
                ];
                $this->movieStaffRepository->create( $newMovieStaff);
            }


            $this->pdo->commit();
        }catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new Exception("Error al crear la película: " . $e->getMessage());
        }
    }
    
    
    public function getAll() : array
    {
        $movies = [];
        $result = $this->movieRepository->getAll();
        foreach ($result as $row) {
            $staffJson = '[' . $row['staff'] . ']'; 
            $staffArray = json_decode($staffJson, true);
        
            $movies[] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'year' => $row['year'],
                'country' => $row['country'],
                'url' => $row['url'],
                'genres' => explode(', ', $row['genres']), 
                'staff' => $staffArray 
            ];
        }
        return $movies;
    }

    public function getById(int $id)
    {
        $result = $this->movieRepository->getById($id);
     
        $staffJson = '[' . $result['staff'] . ']'; 
        $staffArray = json_decode($staffJson, true);
    
        $movies= [
            'id' => $result['id'],
            'title' => $result['title'],
            "year" => $result["year"],
            "country" => $result["country"],
            "url" => $result["url"],
            'genres' => explode(', ', $result['genres']), 
            'staff' => $staffArray 
        ];
        
        return $movies;
    }

    public function update($data)
    {
        try {
            $this->pdo->beginTransaction();
            $idCountry = $this->countryRepository->getIdCountryByName($data["country"]);

            
            if($idCountry === 0)
            {
                $v = $data["country"];
                throw new DataException("El país $v no existe"); 
            }

            $updateMovie = [
                "id"=> $data["id"],
                "title" => $data["title"],
                "id_country" => $idCountry,
                "year" => $data["year"],
                "url" => $data["url"]
            ];

            $genresOld = $this->genreRepository->getGenresByMovieId($data["id"]);
            $genresOldArray = explode(', ', $genresOld['genres']);
           
            $this->movieRepository->update($updateMovie );
            
            $idMovie = $data["id"];
            foreach($data["genres"] as $genre)
            {
                $idGenre = 0;
                if(!$this->genreRepository->existByName($genre)){
                    throw new DataException("No existe el genero $genre"); 
                }else
                {
                    $idGenre =  $this->genreRepository->getId($genre);
                }

                if(in_array($genre, $genresOldArray))
                {
                    $indice = array_search($genre,  $genresOldArray);
                    unset($genresOldArray[$indice]);
                }else
                {
                    $this->movieGenreRepository->create($idMovie,$idGenre);
                }
            }

            
            if(!empty( $genresOldArray))
            {
                foreach( $genresOldArray as $genre){
                    $this->movieGenreRepository->delete($idMovie,$genre);

                }
            }

            $this->pdo->commit();
        }catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new Exception("Error al actualizar la película: " . $e->getMessage());
        }
    }
    public function delete($title){
        $this->movieRepository->delete($title);
    }

    public function exists(int $id): bool
    {
        return $this->movieRepository->exists($id);;
    }


}