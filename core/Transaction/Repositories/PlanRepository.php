<?php

namespace Core\Transaction\Repositories;

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

use Core\Transaction\Model\Plan;
use Elyerr\ApiResponse\Assets\Asset;
use Elyerr\ApiResponse\Assets\JsonResponser;

class PlanRepository
{
    use JsonResponser, Asset;

    /**
     * Model
     * @var Plan
     */
    public $model;

    /**
     * Constructor
     * @param Plan $plan
     */
    public function __construct(Plan $plan)
    {
        $this->model = $plan;
    }

    /**
     * Query Builder
     * @return \Illuminate\Database\Eloquent\Builder<Plan>
     */
    public function query()
    {
        $query = $this->model->query();

        $query->with([
            'scopes',
            'prices',
            'scopes.role',
            'scopes.service.group'
        ]);

        return $query;
    }

    /**
     * Find plan by id
     * @param string $id
     * @return Plan|\Illuminate\Database\Eloquent\Builder<Plan>|\Illuminate\Database\Eloquent\Builder<Plan>[]|\Illuminate\Database\Eloquent\Collection<int, Plan>|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(string $id)
    {
        return $this->query()->find($id);
    }

    /**
     * Create new resource
     * @param array $data
     * @return Plan|TModel|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update plan
     * @param string $id
     * @param array $data
     * @return Plan|\Illuminate\Database\Eloquent\Builder<Plan>|\Illuminate\Database\Eloquent\Builder<Plan>[]|\Illuminate\Database\Eloquent\Collection<int, Plan>|\Illuminate\Database\Eloquent\Model|null
     */
    public function update(string $id, array $data)
    {
        $plan = $this->find($id);

        $plan->update($data);

        return $plan;
    }
}
