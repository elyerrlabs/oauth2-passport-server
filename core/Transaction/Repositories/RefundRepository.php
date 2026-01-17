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

use Core\Transaction\Model\Refund;

class RefundRepository
{
    /**
     * Model
     * @var
     */
    private $model;

    /**
     * String
     * @var string
     */
    private $storage;
    /**
     * Construct
     * @param \Core\Transaction\Model\Refund $refund
     */
    public function __construct(Refund $refund)
    {
        $this->model = $refund;
        $this->storage = "refunds";
    }

    /**
     * Get Storage
     * @return string
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * Query
     * @return \Illuminate\Database\Eloquent\Builder<Refund>
     */
    public function query()
    {
        $query = $this->model->query();

        $query->with(
            [
                'parentTransaction',
                'transaction',
                'appeal',
                'files',
                'user',
                'assignedTo',
                'assignedBy',
            ]
        );

        return $query;
    }


    /**
     * Find specific  resource
     * @param string $id
     * @return Refund|TValue|null
     */
    public function find(string $id)
    {
        return $this->query()->where('id', $id)->first();
    }

    /**
     * Find refund for user
     * @param string $id
     * @return Refund|TValue|null
     */
    public function findForUser(string $id)
    {
        return $this->query()->where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->first();
    }

    /**
     * Create new resource
     * @param array $data
     * @return Refund|TModel|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update resources
     * @param string $id
     * @param array $data
     * @return Refund|TValue|null
     */
    public function update(string $id, array $data)
    {
        $model = $this->find($id);

        $model->update($data);

        return $model;
    }
}
