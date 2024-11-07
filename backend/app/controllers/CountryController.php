<?php

namespace app\controllers;
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