<?php
namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

interface BaseContract
{
    public function create(array $data): Model;
    
    public function update(array $data, int $id): Model;

    public function find(int $id): Model;

    public function delete (int $id): bool;

    public function all(): Collection
    
}