<?php

namespace App\Repositories;

use App\Contracts\BaseContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;


abstract class BaseRepository implements BaseContract
{
    /**
     * The model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create(array $data): Model
    {
       return $this->model->create($data);
    }
    
    public function update(array $data, int $id): Model
    {

    }

    public function find(int $id): Model
    {

    }

    public function delete (int $id): bool
    {

    }

    public function all(): Collection{
        return $this->model->get();

    }

}