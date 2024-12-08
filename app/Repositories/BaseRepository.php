<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class BaseRepository
{
    abstract protected function getModelClass(): string;

    final protected function getModelInstance(): Model
    {
        $modelClass = $this->getModelClass();

        return new $modelClass();
    }

    public function getQuery(): Builder
    {
        return ($this->getModelClass())::query();
    }

    public function get($columns = ['*']): Collection
    {
        /** @var Builder $query */
        $query = ($this->getModelClass())::query();

        $query->select($columns);

        return $query->get();
    }

    /**
     * @param $value
     * @param string $attribute
     * @return mixed
     */
    public function getOne($value, string $attribute = 'id'): mixed
    {
        $query = ($this->getModelClass())::where($attribute, '=', $value);

        return $query->first();
    }

    public function getOneByConditions(array $conditions, $with = null, ?array $order = null)
    {
        /** @var Builder $query */
        $query = ($this->getModelClass())::query();

        foreach ($conditions as $key => $value) {
            if (is_array($value)) {
                $query->whereIn($key, $value);
            } else {
                $query->where($key, $value);
            }
        }

        if (!empty($with)) {
            $query->with($with);
        }

        if ($order) {
            $orderDirection = $order['direction'] ?? 'asc';
            $orderColumn = is_array($order) ? $order['column'] : $order;

            $query->orderBy($orderColumn, $orderDirection);
        }

        return $query->first();
    }

    public function getByConditions(array $conditions, $with = null)
    {
        $query = ($this->getModelClass())::query();

        foreach ($conditions as $key => $value) {
            if (is_array($value)) {
                $query->whereIn($key, $value);
            } else {
                $query->where($key, $value);
            }
        }

        if (!empty($with)) {
            $query->with($with);
        }

        return $query->get();
    }

    /**
     * @throws \Exception
     */
    public function save(Model $model)
    {
        if ($model->save()) {
            return $model;
        }

        throw new \Exception('Cannot save model ' . $this->getModelClass());
    }

    /**
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function create(array $data)
    {
        $modelClass = $this->getModelClass();
        $model = new $modelClass();
        $model->fill($data);

        if ($model->save()) {
            return $model;
        }

        throw new \Exception('Cannot create model ' . $this->getModelClass());
    }

    /**
     * @param Model $model
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function update(Model $model, array $data)
    {
        $model->fill($data);

        if ($model->save()) {
            return $model;
        }
        throw new \Exception('Cannot update model ' . $this->getModelClass());
    }

    /**
     * @param array $conditions List of conditions
     * @param array $data
     * @return bool
     */
    public function updateByConditions(array $conditions, array $data): bool
    {
        /** @var Model $instance */
        $instance = ($this->getModelClass())::where($conditions)->first();

        if ($instance) {
            $instance->fill($data);
            return $instance->save();
        }

        return false;
    }

    public function new(array $initialAttributes = [])
    {
        $modelClass = $this->getModelClass();
        $model = new $modelClass();
        $model->forceFill($initialAttributes);

        return $model;
    }
}
