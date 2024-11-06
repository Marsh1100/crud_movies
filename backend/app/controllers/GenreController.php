<?php

namespace app\controllers;

use app\interfaces\GenreInterface;

class GenreController {
    private GenreInterface $genre;
    public function __construct(GenreInterface $genre)
    {
        $this->genre = $genre;
    }
    public function get( ) : array
    {
        return $this->genre->get();
    }
   

}