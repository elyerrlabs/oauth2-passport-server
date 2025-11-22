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

use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;
use Core\Ecommerce\Model\Variant;
use Core\Ecommerce\Services\ProductService;
use Core\Transaction\Model\DeliveryAddress;
use Core\Ecommerce\Repositories\OrderRepository;
use Core\Transaction\Repositories\UserRepository;
use Core\Transaction\Services\TransactionService;
use Core\Ecommerce\Repositories\CheckoutRepository;

class CheckoutService
{

    /**
     * Checkout repository
     * @var CheckoutRepository
     */
    private $checkoutRepository;

    /**
     * User Repository
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Product service
     * @var ProductService
     */
    private $productService;

    /**
     * Order repository
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * Transaction service
     * @var TransactionService
     */
    private $transactionService;


    public function __construct()
    {
        $this->checkoutRepository = app(CheckoutRepository::class);
        $this->userRepository = app(UserRepository::class);
        $this->productService = app(ProductService::class);
        $this->orderRepository = app(OrderRepository::class);
        $this->transactionService = app(TransactionService::class);
    }

    /**
     * Searcher
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder<\Core\Transaction\Model\Checkout>
     */
    public function searchForUser(Request $request)
    {
        $query = $this->checkoutRepository->query();

        $query->orderByDesc('created_at');

        $query->whereHas(
            'orders',
            function ($query) {
                $query->where('user_id', auth()->user()->id);
            }
        );

        return $query;
    }

    /**
     * Searcher for admins
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder<Checkout>
     */
    public function search(Request $request)
    {
        // Create query
        $query = $this->checkoutRepository->query();

        // Order by latest
        $query->orderByDesc('created_at');

        // Filter by code
        if ($request->filled('code')) {
            $query->where('code', $request->code);
        }

        // Filter by transaction code and status
        $query->whereHas(
            'lastTransaction',
            function ($query) use ($request) {
                if ($request->filled('transaction_code')) {
                    $query->where('code', $request->transaction_code);
                }

                $status = $request->filled('status') ? $request->status : config('billing.status.successful.id');
                $query->where('status', $status);
            }
        );

        // Filter by user
        if ($request->filled('user_id')) {
            $query->whereHas(
                'orders',
                function ($query) use ($request) {
                    $query->where('user_id', $request->user_id);
                }
            );
        }

        return $query;
    }

    /**
     * List customers
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder<Checkout>
     */
    public function listCustomers(Request $request)
    {
        // Create query
        $query = $this->userRepository->query();

        // Only users with successful transactions
        $query->whereHas(
            'checkouts.transactions',
            function ($query) {
                $query->where('status', 'successful');
            }
        );

        // Filter by user id
        if ($request->filled('user_id')) {
            $query->where('id', $request->user_id);
        }

        return $query;
    }

    /**
     * Details
     * @param string $id
     * @throws ReportError
     * @return object|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null
     */
    public function details(string $id)
    {
        try {
            return $this->checkoutRepository->find($id);
        } catch (\Throwable $th) {
            throw new ReportError(__("Error retrieving checkout details."), 404);
        }
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
            ->groupBy('variant_id')
            ->map(function ($items) {
                return [
                    'variant_id' => $items->first()['variant_id'],
                    'total_quantity' => $items->sum('quantity'),
                ];
            });

        //check total stock
        foreach ($products as $item) {
            $this->productService->verifyStock(
                $item['variant_id'],
                $item['total_quantity']
            );
        }

        // Set delivery address
        $delivery = DeliveryAddress::find($data['delivery'])->toArray();
        unset($delivery['id']);
        unset($delivery['user_id']);
        unset($delivery['created_at']);
        unset($delivery['updated_at']);

        //Generate checkout
        $checkout = $this->checkoutRepository->create([
            'delivery_address' => $delivery,
            'status' => config('billing.status.pending.id'),
            'code' => $checkout_code = $this->checkoutRepository->generateCheckoutCode(),
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
            $product = Variant::with(
                [
                    'variantable',
                    'price'
                ]
            )->find($item['variant_id'])->toArray();

            $checkout['items'][] = [
                'price_data' => [
                    'currency' => strtolower($product['price']['currency']),
                    'unit_amount' => $product['price']['amount'],
                    'product_data' => [
                        'name' => $product['variantable']['name'],
                    ],
                ],
                'quantity' => $item['total_quantity'],
            ];
        }

        // Set the checkout order code
        $checkout['checkout_code'] = $checkout_code;
        $checkout['billing_period'] = config('billing.period.one_time.id');
        $checkout['payment_method'] = $data['payment_method'];
        $checkout['user_id'] = auth()->user()->id;

        unset($checkout['code']);

        return $this->transactionService->buy($checkout);
    }




}
