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

use Core\Transaction\Model\DeliveryAddress;

class DeliveryAddressRepository
{
    /**
     * Model
     * @var 
     */
    private $model;


    public function __construct(DeliveryAddress $deliveryAddress)
    {
        $this->model = $deliveryAddress;
    }

    /**
     * Query
     * @return \Illuminate\Database\Eloquent\Builder<DeliveryAddress>
     */
    public function query()
    {
        return $this->model->query()->where('user_id', request()->user()->id);
    }

    /**
     * Create new resource
     * @param array $data
     * @return DeliveryAddress|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * find resource
     * @param string $id
     * @return DeliveryAddress|object|TValue|\stdClass|null
     */
    public function find(string $id)
    {
        return $this->model->query()
            ->where('id', $id)
            ->first();
    }

    /**
     * Update specific resource
     * @param string $id
     * @param array $data
     * @return DeliveryAddress|\Illuminate\Database\Eloquent\Model
     */
    public function update(string $id, array $data)
    {
        $model = $this->find($id);

        $model->fill($data);

        $model->push();

        return $model;
    }
}
