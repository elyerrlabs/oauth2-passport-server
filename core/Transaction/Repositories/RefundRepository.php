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

use Core\Transaction\Model\Refund;

class RefundRepository
{
    /**
     * Model
     * @var
     */
    private $model;

    /**
     * Construct
     * @param \Core\Transaction\Model\Refund $refund
     */
    public function __construct(Refund $refund)
    {
        $this->model = $refund;
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
                'transactions',
                'appeal',
                'refund',
                'user',
                'handledBy'
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
