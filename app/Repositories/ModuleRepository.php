<?php

namespace App\Repositories;

use App\Models\Module;

final class ModuleRepository
{

    /**
     * Module 
     * @var Module
     */
    protected $model;

    public function __construct()
    {
        $this->model = app(Module::class);
    }

    /**
     * Query
     * @return \Illuminate\Database\Eloquent\Builder<Module>
     */
    public function query()
    {
        return $this->model->query();
    }

    /**
     * Find resource
     * @param string $id
     * @return Module|object|TValue|\stdClass|null
     */
    public function find(string $id)
    {
        return $this->query()->where('id', $id)->first();
    }

    /**
     * Find by name
     * @param string $name
     * @return Module|object|TValue|\stdClass|null
     */
    public function findByName(string $name)
    {
        return $this->query()->where('name', $name)->first();
    }


    /**
     * Create new resource
     * @param array $data
     * @return Module|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update resource
     * @param string $id
     * @param array $data
     * @return Module|object|TValue|\stdClass|null
     */
    public  function update(string $id, array $data)
    {
        $model = $this->find($id);

        $model->fill($data);

        return $model;
    }
}
