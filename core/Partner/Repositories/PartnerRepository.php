<?php

namespace Core\Partner\Repositories;

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
