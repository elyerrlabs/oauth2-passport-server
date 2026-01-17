<?php

namespace Core\Partner\Repositories;

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

use Core\Partner\Model\Partner;
use Core\Partner\Transformer\PartnerTransformer;

class PartnerRepository
{
    /**
     * Transformer
     * @var PartnerTransformer
     */
    public $transformer = PartnerTransformer::class;

    /**
     * Model
     * @var Partner
     */
    public $model;

    /**
     * Construct
     * @param \Core\Partner\Model\Partner $partner
     */
    public function __construct(Partner $partner)
    {
        $this->model = $partner;
    }

    /**
     * Query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<Partner>
     */
    public function query()
    {
        return $this->model->query();
    }

    /**
     * Search specific resource
     * @param string $id
     * @return Partner|Partner[]|TModel|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(string $id)
    {
        return $this->model->find($id);
    }

    /**
     * Search partner by code
     * @param string $code
     * @return TModel|TValue|null
     */
    public function findByCode(string $code)
    {
        return $this->model->where('code', $code)->first();
    }

    /**
     * Create 
     * @param array $data
     * @return Partner|TModel|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update partner
     * @param string $id
     * @param array $data
     * @return Partner|Partner[]|TModel|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function update(string $id, array $data)
    {
        $partner = $this->find($id);
        $partner->update($data);
        $partner->push();
        return $partner;
    }

}
