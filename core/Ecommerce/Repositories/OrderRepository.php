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

use App\Models\Common\Order;
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
     * @param \App\Models\Common\Order $order
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

        $query->with('user', 'orderable', 'orderable.files', 'orderable.price');

        $query->where('status', config('billing.status.pending.name'));

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
            $product = $this->productRepository->find($data['product_id']);

            /* Change this action when user paid the product
            $product->setStock($product->stock - $data['quantity']);
             $product->push();

              if (count($data['attrs'] ?? [])) {
                  foreach ($data['attrs'] as $key => $value) {

                      $attribute = $product->attributes->where('slug', $key)->where('value', $value)->first();

                      $update_attribute_stock = $attribute->pivot->stock - $data['quantity'];

                      $product->attributes()->syncWithoutDetaching([$attribute->id => ['stock' => $update_attribute_stock]]);
                  }
              }*/

            $meta = $product->toArray();
            unset($meta['attributes']);
            unset($meta['tags']);
            unset($meta['files']);
            unset($meta['category']['created_at']);
            unset($meta['category']['updated_at']);
            $meta['attributes'] = $data['attrs'];
            $data['meta'] = $meta;
            unset($data['attrs']);
            
            return $product->orders()->create($data);
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
            return $this->model->findOrFail($id);
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), [
                'code' => $th->getCode(),
                'trace' => $th->getTrace()
            ]);
        }

        return null;
    }

    /**
     * Update order
     * @param string $id
     * @param array $data
     * @return bool
     */
    public function update(string $id, array $data)
    {
        return $this->model->update(['id' => $id], $data);
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
