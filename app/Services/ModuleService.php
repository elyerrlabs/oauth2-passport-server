<?php

namespace App\Services;

use App\Repositories\ModuleRepository;

final class ModuleService
{

    /**
     * Construct
     * @param ModuleRepository $moduleRepository
     */
    public function __construct(protected ModuleRepository $moduleRepository)
    {
    }

    public function all()
    {
        return $this->moduleRepository->query();
    }

    /**
     * Find resource
     * @param string $id
     * @return \App\Models\Module
     */
    public function find(string $id)
    {
        return $this->moduleRepository->query()->where('id', $id)->first();
    }

    /**
     * Find by name
     * @param string $name
     * @return  \App\Models\Module
     */
    public function findByName(string $name)
    {
        return $this->moduleRepository->query()->where('name', $name)->first();
    }

    /**
     * Create
     * @param array $data
     * @return \App\Models\Module
     */
    public function create(array $data)
    {
        return $this->moduleRepository->create($data);
    }

    /**
     * Update
     * @param string $id
     * @param array $data
     * @return \App\Models\Module|null
     */
    public function update(string $id, array $data)
    {
        return $this->moduleRepository->update($id, $data);
    }

    /**
     * Delete
     * @param string $id 
     * @return bool
     */
    public function delete(string $id)
    {
        return $this->moduleRepository->delete($id);
    }
}
