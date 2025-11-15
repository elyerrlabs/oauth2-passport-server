<?php
namespace Core\User\Repositories;

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

use Core\User\Model\Service;

class ServiceRepository
{
    /**
     * Model
     * @var Service
     */
    public $model;

    /**
     * Construct
     * @param \Core\User\Model\Service $service
     */
    public function __construct(Service $service)
    {
        $this->model = $service;
    }

    /**
     * Query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<Service>
     */
    public function query()
    {
        $query = $this->model->query();
        $query->with([
            'group',
            'scopes',
            'scopes.role'
        ]);
        return $query;
    }

    /**
     * Create new resource
     * @param array $data
     * @return Service|TModel|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Find resource
     * @param string $id
     * @return TModel|TValue|null
     */
    public function find(string $id)
    {
        return $this->query()->where('id', $id)->first();
    }

    /**
     * Update
     * @param string $id
     * @param array $data
     * @return TModel|TValue|null
     */
    public function update(string $id, array $data)
    {
        $service = $this->find($id);

        $service->update($data);

        return $service;
    }
}
