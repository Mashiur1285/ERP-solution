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
        $model= $this->model->findOrFail($id);
        if(!$model->update())
        {
            return null;
        }
        return $model->refresh();
    }

    public function find(int $id): Model
    {
      
         return $this->model->findOrFail($id);
    }

    public function delete (int $id): bool
    {
       return $this->model->query()->findOrFail($id)->delete();
    }

    public function all(): Collection{
        return $this->model->get();

    }

}