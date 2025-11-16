<?php

namespace App\Repositories;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function get(Request $request): Builder
    {
        return $this->model->newQuery();
    }

    public function getAll(): Collection
    {
        return $this->model->get();
    }

    public function find(int $id): ?Model
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update($id, array $data): ?Model
    {
        $record = $this->find($id);

        return $record ? tap($record)->update($data) : null;
        // $record->fill($data);
        // $record->save();
        // return $record->fresh();
    }

    public function delete($id): bool
    {
        $record = $this->find($id);

        return $record ? (bool) $record->delete() : false;
    }
}
