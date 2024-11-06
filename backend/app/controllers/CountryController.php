<?php

namespace app\controllers;
use app\interfaces\MovieInterface;

use app\exceptions\ValidationException;
use app\interfaces\ValidatorInterface;

use app\exceptions\DataException;
use app\interfaces\CountryInterface;

class CountryController {
    private CountryInterface $country;
    public function __construct(CountryInterface $country)
    {
        $this->country = $country;
    }
    public function get( ) : array
    {
        return $this->country->get();
    }
   

}