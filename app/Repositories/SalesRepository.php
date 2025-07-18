<?php

namespace App\Repositories;

use App\Contracts\SalesContract;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class SalesRepository extends BaseRepository implements SalesContract
{
    public function __construct(Sale $model)
    {
        parent::__construct($model);
    }

    public function query(): Builder
    {
        return $this->model->query();
    }

    // $this->salesRepository->updateSales($data);
    public function updateSales(array $data, int $id): Model
    {
        $sale = $this->find($id);
        $sale->update($data);
        $sale->save();
        return $sale;
    }
}
