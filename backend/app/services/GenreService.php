<?php

namespace app\services;

use app\interfaces\GenreInterface;
use app\repositories\GenreRepository;

class GenreService implements GenreInterface 
{

    private GenreRepository $genreRepository;


    public function __construct( GenreRepository $genreRepository)
    {        
      
        $this->genreRepository = $genreRepository;
    }

    
    public function get() : array
    {

        $countries = [];

        foreach( $this->genreRepository->get() as $genre)
        {
            $countries[] = $genre["name"];
        }
        return $countries;
       

    }

   

    


}