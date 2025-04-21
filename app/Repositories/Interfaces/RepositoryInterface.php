<?php

namespace App\Repositories\Interfaces;

interface RepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function paginate($perPage = 15, array $columns = ['*']);
    public function with($relations);
}