<?php

namespace app\services;

use app\interfaces\CountryInterface;
use app\repositories\CountryRepository;


class CountryService implements CountryInterface 
{
    private CountryRepository $countryRepository;


    public function __construct( CountryRepository $countryRepository)
    {        
      
        $this->countryRepository = $countryRepository;
    }

    
    public function get() : array
    {

        $countries = [];

        foreach( $this->countryRepository->get() as $country)
        {
            $countries[] = $country["name"];
        }
        return $countries;
       

    }

   

    


}