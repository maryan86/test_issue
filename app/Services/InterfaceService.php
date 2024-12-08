<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface InterfaceService
{
    public function getAll(array $columns = ['*']): Collection;

    public function update(Model $model, array $data): Model;

    public function create(array $data): Model;

}
