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

use Core\Ecommerce\Transformer\Admin\VariantTransformer;
use Core\Ecommerce\Model\Variant;
use Core\Ecommerce\Model\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Core\Ecommerce\Repositories\ProductRepository;

class OrderRepository
{
    /**
     * Model
     * @var
     */
    private $model;

    private $productRepository;

    /**
     * Construct
     * @param \Core\Ecommerce\Model\Order $order
     * @param \Core\Ecommerce\Repositories\ProductRepository $productRepository
     */
    public function __construct(Order $order, ProductRepository $productRepository)
    {
        $this->model = $order;
        $this->productRepository = $productRepository;
    }

    /**
     * Create query
     * @return \Illuminate\Database\Eloquent\Builder<Order>
     */
    public function query()
    {
        $query = $this->model->query();

        $query->with([
            'user',
            'orderable',
            'orderable.variantable.files',
            'orderable.price'
        ]);

        return $query;
    }


    /**
     * Create order
     * @param array $data
     * @return Order|TModel|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Find Order
     * @param string $id
     * @return object|\Illuminate\Database\Eloquent\Builder<Order>|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(string $id)
    {
        return $this->query()->where('id', $id)->first();
    }

    /**
     * Update order
     * @param string $id
     * @param array $data
     * @return object|\Illuminate\Database\Eloquent\Builder<Order>|\Illuminate\Database\Eloquent\Model|null
     */
    public function update(string $id, array $data)
    {
        $order = $this->find($id);
        $order->update($data);
        return $order;
    }
}
