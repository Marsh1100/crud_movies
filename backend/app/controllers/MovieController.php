<?php

namespace app\controllers;
use app\interfaces\MovieInterface;

use app\exceptions\ValidationException;
use app\interfaces\ValidatorInterface;

use app\exceptions\DataException;

class MovieController {
    private MovieInterface $movie;
    private ValidatorInterface $validator;
    public function __construct(MovieInterface $movie, ValidatorInterface $validator)
    {
        $this->movie = $movie;
        $this->validator = $validator;
    }
    public function get( ) : array
    {
        return $this->movie->getAll();
    }
    public function getById($id)
    {
        if(!$this->movie->exists($id))
        {
            throw new DataException("No existe el id $id");
        }
        return $this->movie->getById($id);
    }
    public function add($data)
    {
        if(!$this->validator->validateAdd($data))
        {
            throw new ValidationException($this->validator->getError());
        }

        $this->movie->create($data);
    }
    public function update($data)
    {
        if(!$this->validator->validateUpdate($data))
        {
            throw new ValidationException($this->validator->getError());
        }
        if(!$this->movie->exists($data['id']))
        {
            throw new DataException("No existe el dato a actualizar");
        }
        $this->movie->update($data);
    }

    public function delete(int|null $id)
    {
        if(!$this->validator->validateExist($id))
        {
            throw new DataException("No está definido el id"); 
        }
        if(!$this->movie->exists($id))
        {
            throw new DataException("No existe el dato a eliminar");
        }
        return $this->movie->delete($id);
    }

}