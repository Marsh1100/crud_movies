<?php
namespace app\interfaces;

interface StaffInterface {
    public function create($data);
    public function get(int|null $id):array;
    public function delete(int $id);
    public function exists(int $id): bool;
    public function existByName(string $name): bool;

}