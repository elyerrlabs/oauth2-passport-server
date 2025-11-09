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

use Core\User\Model\Role;
use Elyerr\ApiResponse\Assets\Asset;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Core\User\Transformer\Admin\RoleTransformer;


class RoleRepository
{
    /**
     * Transformer class
     * @var 
     */
    public $transformer = RoleTransformer::class;

    /**
     * Model
     * @var Role
     */
    private $model;

    /**
     * Construct
     * @param \Core\User\Model\Role $role
     */
    public function __construct(Role $role)
    {
        $this->model = $role;
    }

    /**
     * Query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<Role>
     */
    public function query()
    {
        return $this->model->query();
    }

    /**
     * Create new resource
     * @param array $data
     * @return Role|TModel|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Search specific resource
     * @param string $id
     * @return Role
     */
    public function find(string $id)
    {
        return $this->model->find($id);
    }

    /**
     * Update specific resource
     * @param string $id
     * @param array $data
     * @return Role|Role[]|TModel|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function update(string $id, array $data)
    {
        $model = $this->model->find($id);

        $model->update($data);

        return $model;
    }


}
