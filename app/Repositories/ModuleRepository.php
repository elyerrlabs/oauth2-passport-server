<?php

namespace App\Repositories;

use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Filesystem\Filesystem;
use App\Models\Module;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

final class ModuleRepository
{
    /**
     * Filesystem instance.
     */
    protected Filesystem $files;

    /**
     * Manifest path.
     */
    protected string $path;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;

        $this->path = storage_path('app/modules.json');
    }

    /**
     * Get the all modules
     * @return Collection
     */
    public function query()
    {
        return new Collection(array_map(function ($item) {
            return new Module($item);
        }, $this->read()));
    }


    /**
     * Migrate database to modules.json file
     * @param array $data
     * @return void
     */
    public function migrate(array $data)
    {
        $data = new Module($data);
        $data->setEnabled(true);
        $this->create($data->toArray());
    }

    /**
     * Create module.
     * @param array $data
     * @return Module
     */
    public function create(array $data)
    {
        $modules = $this->query() ?? new Collection();

        if ($modules->where('name', $data['name'])->first()) {
            throw new ReportError(
                __('Module creation failed, alredy exist module with name :name', [
                    'name' => $data['name']
                ]),
                422
            );
        }

        $module = new Module($data);
        $module->setId((string) Str::uuid());
        $modules->add($module);

        $this->write(
            $modules
                ->map(fn(Module $module) => $module->toArray())
                ->all()
        );

        return $module;
    }

    /**
     * Update module
     * @param string $id
     * @param array $data
     * @return Module|null
     */
    public function update(string $id, array $data): ?Module
    {
        $modules = $this->query();

        /** @var Module|null $module */
        $module = $modules->first(fn(Module $module) => $module->getId() === $id);

        if ($module === null) {
            return null;
        }

        if (isset($data['name'])) {
            $module->setName($data['name']);
        }

        if (isset($data['provider'])) {
            $module->setProvider($data['provider']);
        }

        if (isset($data['source'])) {
            $module->setSource($data['source']);
        }

        if (isset($data['path'])) {
            $module->setPath($data['path']);
        }

        if (isset($data['username'])) {
            $module->setUsername($data['username']);
        }

        if (isset($data['token'])) {
            $module->setToken($data['token']);
        }

        if (isset($data['current_version'])) {
            $module->setCurrentVersion($data['current_version']);
        }

        if (isset($data['last_version'])) {
            $module->setLastVersion($data['last_version']);
        }

        if (isset($data['new_version'])) {
            $module->setNewVersion($data['new_version']);
        }

        if (isset($data['enabled'])) {
            $module->setEnabled($data['enabled']);
        }

        $this->write(
            $modules
                ->map(fn(Module $module) => $module->toArray())
                ->values()
                ->query()
        );

        return $module;
    }

    /**
     * Delete module
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        $modules = $this->query()->reject(fn($module) => $module->getId() === $id)->values()->toArray();

        $this->write($modules);

        return true;
    }

    /**
     * Read manifest.
     */
    protected function read(): array
    {
        if (!$this->files->exists($this->path)) {
            return [];
        }

        return json_decode(
            $this->files->get($this->path),
            true
        ) ?: [];
    }

    /**
     * Write manifest.
     */
    protected function write(array $modules): void
    {
        $this->files->ensureDirectoryExists(dirname($this->path));

        $this->files->put(
            $this->path,
            json_encode($modules, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }
}