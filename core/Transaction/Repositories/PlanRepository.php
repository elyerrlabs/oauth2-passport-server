<?php

namespace Core\Transaction\Repositories;

/**
 * OAuth2 Passport Server — a centralized, modular authorization server
 * implementing OAuth 2.0 and OpenID Connect specifications.
 *
 * Copyright (c) 2026 Elvis Yerel Roman Concha
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * Author: Elvis Yerel Roman Concha
 * Contact: yerel9212@yahoo.es
 *
 * SPDX-License-Identifier: AGPL-3.0-or-later
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
