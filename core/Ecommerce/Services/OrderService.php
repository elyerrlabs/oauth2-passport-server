<?php

namespace Core\Ecommerce\Services;

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

use Illuminate\Http\Request;
use Core\Ecommerce\Model\Variant;
use Core\Ecommerce\Repositories\OrderRepository;
use Core\Ecommerce\Transformer\Admin\VariantTransformer;

class OrderService
{
    /**
     * Order repository
     * @var  OrderRepository
     */
    private $orderRepository;


    public function __construct()
    {
        $this->orderRepository = app(OrderRepository::class);
    }

    /**
     * Search unpaid orders for user
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder<Order>
     */
    public function searchForUser(Request $request)
    {
        $query = $this->orderRepository->query();

        $query->where('user_id', auth()->user()->id);

        $query->whereNull('checkout_id');

        if ($request->filled('quantity')) {
            $query->where('quantity', $request->quantity);
        }

        return $query;
    }


    /**
     * Create new Order
     * @param array $data
     * @return Order|TModel|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        $variant = Variant::find($data['variant_id']);

        $data['meta'] = $variant->variantable->toArray();
        $data['meta']['category'] = $variant->variantable->category->toArray();
        $data['meta']['variant'] = fractal($variant, VariantTransformer::class)->toArray()['data'];

        unset($data['meta']['variant']['links']);

        return $variant->orders()->create($data);
    }

    /**
     * Update order
     * @param string $id
     * @param array $data
     * @return object|\Illuminate\Database\Eloquent\Builder<\Core\Ecommerce\Model\Order>|\Illuminate\Database\Eloquent\Model|null
     */
    public function update(string $id, array $data)
    {
        return $this->orderRepository->update($id, $data);
    }

    /**
     * Order repository
     * @param string $id
     * @return object|\Illuminate\Database\Eloquent\Builder<\Core\Ecommerce\Model\Order>|\Illuminate\Database\Eloquent\Model|null
     */
    public function delete(string $id)
    {
        $order = $this->orderRepository->find($id);

        if (!empty($order) && empty($order->transaction_id)) {
            $order->delete();
        }

        return $order;
    }
}
