<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements RepositoryInterface
{
    protected $model;
    
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    
    public function all(array $columns = ['*'])
    {
        return $this->model->all($columns);
    }
    
    public function find($id)
    {
        return $this->model->findOrFail($id);
    }
    
    public function create(array $data)
    {
        return $this->model->create($data);
    }
    
    public function update($id, array $data)
    {
        $record = $this->find($id);
        $record->update($data);
        return $record;
    }
    
    public function delete($id)
    {
        return $this->find($id)->delete();
    }
    
    public function paginate($perPage = 15, array $columns = ['*'])
    {
        return $this->model->paginate($perPage, $columns);
    }
    
    public function with($relations)
    {
        return $this->model->with($relations);
    }

    public function orderBy($column, $direction = 'asc')
    {
        return $this->model->orderBy($column, $direction);
    }

    public function where($column, $value, $operator = '=')
    {
        return $this->model->where($column, $operator, $value);
    }
}