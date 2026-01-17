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

use Core\Transaction\Model\Package;
use Elyerr\ApiResponse\Exceptions\ReportError;

class PackageRepository
{
    /**
     * Model
     * @var Package
     */
    public $model;

    /**
     * Constructor
     * @param  Package $package
     */
    public function __construct(Package $package)
    {
        $this->model = $package;
    }

    /**
     * Query builder
     * @return \Illuminate\Database\Eloquent\Builder<Package>
     */
    public function query()
    {
        $query = $this->model->query();

        $query->with([
            'user',
            'transactions',
            'lastTransaction'
        ]);

        return $query;
    }

    /**
     * Create new resource
     * @param array $data
     * @return Package
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Search specific resource by id
     * @param string $id
     * @return array<\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<Package>>|Package|TModel|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<Package>|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(string $id)
    {
        return $this->query()->where('id', $id)->first();
    }

    /**
     * Find package for user
     * @param string $id
     * @return Package|null
     */
    public function findForUser(string $id)
    {
        return $this->query()
            ->where('user_id', auth()->user()->id)
            ->where('id', $id)
            ->first();
    }

    /**
     * Find package for user by transaction code
     * @param string $code
     * @return Package|null
     */
    public function findByCodeForUser(string $code)
    {
        return $this->query()
            ->where('user_id', auth()->user()->id)
            ->whereHas(
                'transactions',
                function ($query) use ($code) {
                    $query->Where('code', $code);
                }
            )->first();
    }

    /**
     * Update specific resource
     * @param string $id
     * @param array $data
     * @return array<\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<Package>>|Package|TModel|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<Package>|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function update(string $id, array $data)
    {
        $model = $this->find($id);

        $model->update($data);

        return $model;
    }

    /**
     * Update package for user
     * @param string $id
     * @param array $data
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return Package|null
     */
    public function updateForUser(string $id, array $data)
    {
        $model = $this->findForUser($id);

        if (empty($model)) {
            throw new ReportError(__('Resource cannot be found'), 404);
        }

        $model->update($data);

        return $model;
    }

    /**
     * Delete specific resource
     * @param string $id 
     * @return void
     */
    public function delete(string $id)
    {
        // pass
    }
}
