<?php
namespace app\interfaces;

interface MovieInterface {
    public function create($data) ;
    public function getAll():array;
    public function getById(int $id);
    public function update($data);
    public function delete(int $id);
    public function exists(int $id): bool;
}