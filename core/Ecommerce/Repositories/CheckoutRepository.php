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

use Core\Transaction\Model\Transaction;
use Core\Transaction\Model\User;
use Core\Transaction\Model\DeliveryAddress;
use Core\Transaction\Repositories\TransactionRepository;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Core\Transaction\Model\Checkout;
use App\Repositories\Contracts\Contracts;

class CheckoutRepository implements Contracts
{
    /**
     * Model
     * @var
     */
    private $model;

    /**
     * Order repository
     * @var
     */
    private $orderRepository;


    /**
     * Product repository
     */
    private $productRepository;


    private $transactionRepository;

    /**
     * Construct
     * @param \Core\Transaction\Model\Checkout $checkout
     */
    public function __construct(Checkout $checkout, OrderRepository $orderRepository, ProductRepository $productRepository, TransactionRepository $transactionRepository)
    {
        $this->model = $checkout;
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->transactionRepository = $transactionRepository;
    }
    /**
     * Search resources
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder<Checkout>
     */
    public function search(Request $request)
    {
        $query = $this->model->query();

        $query->orderByDesc('created_at');

        $query->whereHas('orders', function ($query) {
            $query->where('user_id', auth()->user()->id);
        });

        return $query;
    }

    /**
     * Searcher for admins
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder<Checkout>
     */
    public function searchForAdmin(Request $request)
    {
        $query = $this->model->query();

        $query->with(['lastTransaction', 'orders', 'orders.orderable']);

        $query->orderByDesc('created_at');

        if ($request->filled('code')) {
            $query->where('code', $request->code);
        }

        $query->whereHas('lastTransaction', function ($query) use ($request) {
            if ($request->filled('transaction_code')) {
                $query->where('code', $request->transaction_code);
            }

            $status = $request->filled('status') ? $request->status : config('billing.status.successful.id');
            $query->where('status', $status);
        });

        if ($request->filled('user_id')) {
            $query->whereHas('orders', function ($query) {
                $query->where('user_id', auth()->user()->id);
            });
        }

        return $query;
    }

    /**
     * Summary of listCustomers
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder<Checkout>
     */
    public function listCustomers(Request $request)
    {
        $query = User::query();

        $query->with([
            'checkouts',
            'checkouts.transactions'
        ]);

        $query->whereHas(
            'checkouts.transactions',
            function ($query) {
                $query->where('status', 'successful');
            }
        );

        if ($request->filled('user_id')) {
            $query->where('id', $request->user_id);
        }

        return $query;
    }

    /**
     * Create new checkout
     * @param array $data
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function create(array $data)
    {
        // Join the same products
        $products = collect($data['orders'])
            ->groupBy('product_id')
            ->map(function ($items) {
                return [
                    'product_id' => $items->first()['product_id'],
                    'total_quantity' => $items->sum('quantity'),
                ];
            });

        //check total stock
        foreach ($products as $item) {
            $this->productRepository->verifyStock($item['product_id'], $item['total_quantity']);
        }

        // Set delivery address
        $delivery = DeliveryAddress::find($data['delivery'])->toArray();
        unset($delivery['id']);
        unset($delivery['user_id']);
        unset($delivery['created_at']);
        unset($delivery['updated_at']);

        //Generate checkout
        $checkout = $this->model->create([
            'delivery_address' => $delivery,
            'status' => config('billing.status.pending.id'),
            'code' => $checkout_code = $this->generateCheckoutCode(),
            'user_id' => auth()->user()->id,
        ])->toArray();

        // Add checkout id and update stock to the orders
        foreach ($data['orders'] as $item) {
            $this->orderRepository->update(
                $item['id'],
                [
                    'quantity' => $item['quantity'],
                    'checkout_id' => $checkout['id']
                ]
            );
        }

        // Prepare items to pay
        foreach ($products as $item) {
            $product = $this->productRepository->find($item['product_id'])->toArray();

            $checkout['items'][] = [
                'price_data' => [
                    'currency' => strtolower($product['price']['currency']),
                    'unit_amount' => $product['price']['amount'],
                    'product_data' => [
                        'name' => $product['name'],
                    ],
                ],
                'quantity' => $item['total_quantity'],
            ];
        }

        // Set the checkout order code
        $checkout['checkout_code'] = $checkout_code;
        $checkout['billing_period'] = config('billing.period.one_time.id');
        $checkout['payment_method'] = $data['payment_method'];
        unset($checkout['code']);

        return $this->transactionRepository->buy($checkout);
    }

    /**
     * Generate a checkout unique order code
     * @return string
     */
    public function generateCheckoutCode()
    {
        $micro = explode(' ', microtime());
        $timestamp = date('YmdHis', (int) $micro[1]) . substr($micro[0], 2, 3);
        return 'CHK-' . $timestamp . '-' . strtoupper(Str::random(4));
    }

    /**
     * Search specific resource
     * @param string $id
     * @return void
     */
    public function find(string $id)
    {

    }

    /**
     * Update specific resource
     * @param string $id
     * @param array $data
     * @return void
     */
    public function update(string $id, array $data)
    {

    }

    /**
     * Delete specific resource
     * @param string $id
     * @return void
     */
    public function delete(string $id)
    {

    }
}
