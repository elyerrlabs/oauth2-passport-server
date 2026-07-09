<?php

namespace App\Services;

use App\Repositories\ModuleRepository;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

final class ModuleService
{

    /**
     * Construct
     * @param ModuleRepository $moduleRepository
     */
    public function __construct(protected ModuleRepository $moduleRepository)
    {
    }

    /**
     * All
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return $this->moduleRepository->query();
    }

    /**
     * search
     * @param Request $request
     * @return \Illuminate\Support\Collection
     */
    public function search(Request $request)
    {
        $query = $this->all();

        if ($request->filled('name')) {
            $name = strtolower($request->name);

            $query = $query->filter(function ($module) use ($name) {
                return Str::contains(strtolower($module->name), $name);
            });
        }

        return $query;
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
     * Enabled
     * @param string $id
     * @throws ReportError
     * @return \App\Models\Module|null
     */
    public function enabled(string $id)
    {
        $module = $this->find($id);

        throw_if(empty($module), fn() => throw new ReportError(__('Module can not be found'), 404));

        throw_if($module->isEnabled(), fn() => throw new ReportError(__('Module is already enabled'), 403));

        return $this->moduleRepository->update($id, [
            'enabled' => true
        ]);
    }

    /**
     * Enabled
     * @param string $id
     * @throws ReportError
     * @return \App\Models\Module|null
     */
    public function disabled(string $id)
    {
        $module = $this->find($id);

        throw_if(empty($module), fn() => throw new ReportError(__('Module can not be found'), 404));

        throw_if(!$module->isEnabled(), fn() => throw new ReportError(__('Module is already stopped'), 403));

        return $this->moduleRepository->update($id, [
            'enabled' => false
        ]);
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
