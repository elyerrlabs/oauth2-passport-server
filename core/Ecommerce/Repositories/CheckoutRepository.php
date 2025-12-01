<?php

namespace Core\Ecommerce\Repositories;

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

use Illuminate\Support\Str;
use Core\Transaction\Model\Checkout;

class CheckoutRepository
{
    /**
     * Model
     * @var
     */
    private $model;


    /**
     * Construct
     * @param \Core\Transaction\Model\Checkout $checkout
     */
    public function __construct(Checkout $checkout)
    {
        $this->model = $checkout;
    }

    /**
     * Query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<Checkout>
     */
    public function query()
    {
        $query = $this->model->query();

        $query->with([
            'lastTransaction',
            'lastTransaction.refund',
            'lastTransaction.refund.files',
            'orders',
            'orders.orderable'
        ]);

        return $query;
    }

    /**
     * Create resource
     * @param array $data
     * @return Checkout|TModel|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Find by id
     * @param mixed $id
     * @return object|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null
     */
    public function find($id)
    {
        return $this->query()->where('id', $id)->first();
    }

    /**
     * Update resource
     * @param string $id
     * @param array $data
     * @return object|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null
     */
    public function update(string $id, array $data)
    {
        $checkout = $this->find($id);

        $checkout->update($data);

        return $checkout;
    }

    /**
     * Generate unique checkout code
     * @return string
     */
    public function generateCheckoutCode()
    {
        $micro = explode(' ', microtime());
        $timestamp = date('YmdHis', (int) $micro[1]) . substr($micro[0], 2, 3);
        return 'CHK-' . $timestamp . '-' . strtoupper(Str::random(4));
    }
}
