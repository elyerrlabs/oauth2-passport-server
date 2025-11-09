<?php

namespace Core\User\Services;

/**
 * Copyright (c) 2025 Elvis Yerel Roman Concha
 *
 * This file is part of an open source project licensed under the
 * "NON-COMMERCIAL USE LICENSE - OPEN SOURCE PROJECT" (Effective Date: 2025-08-03).
 *
 * You may use, study, modify, and redistribute this file for personal,
 * educational, or non-commercial research purposes only.
 *
 * Commercial use is strictly prohibited without prior written consent
 * from the author.
 *
 * Combining this software with any project licensed for commercial use
 * (such as AGPL) is not permitted without explicit authorization.
 *
 * This software supports OAuth 2.0 and OpenID Connect.
 *
 * Author Contact: yerel9212@yahoo.es
 * 
 * SPDX-License-Identifier: LicenseRef-NC-Open-Source-Project
 */
use Core\User\Model\Role;
use Illuminate\Http\Request;
use Core\User\Repositories\RoleRepository;
use Elyerr\ApiResponse\Exceptions\ReportError;

class RoleService
{
    /**
     * Role repository
     * @var RoleRepository
     */
    private $roleRepository;

    /**
     * Construc
     * @param \Core\User\Repositories\RoleRepository $roleRepository
     */
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Search data
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<Role>
     */
    public function search(Request $request)
    {
        // Prepare query
        $query = $this->roleRepository->query();

        if ($request->filled('name')) {
            $query->whereRaw("LOWER(name) like ?", ["%" . strtolower($request->name) . "%"]);
        }

        if ($request->filled('system')) {
            $query->where('system', $request->system);
        }

        return $query;
    }

    /**
     * Create new resource
     * @param array $data
     * @return JsonResponser
     */
    public function create(array $data)
    {
        return $this->roleRepository->create([
            'name' => $data['name'],
            'slug' => $data['name'],
            'description' => $data['description'],
            'system' => $data['system'] ?? false
        ]);
    }


    /**
     * Update resource
     * @param string $id
     * @param array $data
     * @return Role|Role[]|\Core\User\Repositories\TModel|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function update(string $id, array $data)
    {
        return $this->roleRepository->update($id, [
            'name' => $data['name'],
            'description' => $data['description']
        ]);
    }


    /**
     * Delete resource
     * @param string $id
     * @return Role
     */
    public function delete(string $id)
    {
        $model = $this->roleRepository->find($id);

        collect(Role::rolesByDefault())->map(function ($value, $key) use ($model) {
            throw_if($value->name == $model->name, new ReportError(__("This action cannot be completed because this role is a system role and cannot be deleted."), 403));
        });

        throw_if($model->system, new ReportError(__("This action cannot be completed because this role is a system role and cannot be deleted."), 403));

        throw_if($model->scopes()->count() > 0, new ReportError(__("This action cannot be completed because this role is currently assigned to one or more scopes and cannot be deleted."), 403));

        $model->delete();

        //send event
        return $model;
    }
}
