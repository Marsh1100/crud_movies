<?php

namespace app\validators;

use app\interfaces\ValidatorInterface;
class MovieValidator implements ValidatorInterface
{
    private string $error;

    public function getError() : string{
        return $this->error;
    }

    public function validateAdd($data): bool
    {
        $errors =[];
        if(empty($data['staff']))
        {
            $errors[] = 'El campo staff es obligatorio.';
        }
        $errors = dataValidator($data);
        $this->error = implode(', ', $errors);
        return count($errors)>0 ? false : true;

    }

    public function validateUpdate($data): bool
    {
        if(empty($data['id']))
        {
            $this->error = 'Id es obligatorio';
            return false;
        }
        $errors = dataValidator($data);
        $this->error = implode(', ', $errors);
        return count($errors)>0 ? false : true;
    }

    public function validateExist($id): bool
    {
        return isset($id);
    }
}

function dataValidator($data) : array
{
    $errors = [];
    if (empty($data['title'])) {
       $errors[] = 'El campo título es obligatorio.';
    }
    if (empty($data['year'])) {
       $errors [] = 'El campo año es obligatorio.';
    } elseif (!is_numeric($data['year']) || strlen($data['year']) != 4) {
       $errors [] = 'El año debe ser un número de 4 dígitos.';
    }
    if (empty($data['country'])) {
       $errors [] = 'El campo country es obligatorio. ';
    }
    if(empty($data['genres']))
    {
        $errors[] = 'El campo genres es obligatorio.';
    }
    return $errors;
}
