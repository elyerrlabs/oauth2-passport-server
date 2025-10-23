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
use App\Repositories\Contracts\Contracts;
use Core\Ecommerce\Repositories\ProductRepository;

class OrderRepository implements Contracts
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
     * Search resource
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder<Order>
     */
    public function search(Request $request)
    {
        $query = $this->model->query();

        return $query;
    }

    /**
     * Search unpaid orders for user
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder<Order>
     */
    public function searchForUser(Request $request)
    {
        $query = $this->model->query();

        $query->where('user_id', auth()->user()->id);

        $query->whereNull('checkout_id');

        $query->with([
            'user',
            'orderable',
            'orderable.variantable.files',
            'orderable.price'
        ]);

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
        $order = DB::transaction(function () use ($data) {

            $variant = Variant::find($data['variant_id']);

            $data['meta'] = $variant->variantable->toArray();
            $data['meta']['category'] = $variant->variantable->category->toArray();
            $data['meta']['variant'] = fractal($variant, VariantTransformer::class)->toArray()['data'];

            unset($data['meta']['variant']['links']);

            return $variant->orders()->create($data);
        });

        return $order;
    }

    /**
     * Search order
     * @param string $id
     * @return Order|Order[]|TModel|\Illuminate\Database\Eloquent\Collection<int, TModel>|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(string $id)
    {
        try {
            return $this->model->find($id);
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), [
                'code' => $th->getCode(),
                'trace' => $th->getTrace()
            ]);
        }

        return null;
    }

    /**
     * Summary of update
     * @param string $id
     * @param array $data
     * @return bool
     */
    public function update(string $id, array $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * Delete order
     * @param string $id
     * @return Order|Order[]|TModel|\Illuminate\Database\Eloquent\Collection<int, TModel>|\Illuminate\Database\Eloquent\Model|null
     */
    public function delete(string $id)
    {
        $order = $this->find($id);

        if (!empty($order) && empty($order->transaction_id)) {
            $order->delete();
        }

        return $order;
    }
}
