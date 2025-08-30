<?php

namespace Core\Ecommerce\Http\Controllers\Web;

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

use Core\Ecommerce\Transformer\User\UserOrderTransformer;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core\Ecommerce\Repositories\OrderRepository;
use Core\Ecommerce\Http\Requests\Order\StoreRequest;

class OrderController extends Controller
{

    private $repository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->repository = $orderRepository;
    }

    /**
     * Index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request)
    {

        if ($request->wantsJson()) {
            $query = $this->repository->searchForUser($request);

            return $this->showAllByBuilder($query, UserOrderTransformer::class);
        }

        return Inertia::render(
            'Core/Ecommerce/Web/Orders',
            [
                'routes' => [
                    'orders' => route('ecommerce.orders.index'),
                    'search' => route('ecommerce.search'),
                    'categories' => route('api.ecommerce.categories.index'),
                ]
            ]
        );
    }

    /**
     * Add new order
     * @param \Core\Ecommerce\Http\Requests\Order\StoreRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $request->merge([
            'user_id' => auth()->user()->id,
        ]);

        $model = $this->repository->create($request->toArray());

        return $this->showOne($model, UserOrderTransformer::class, 201);
    }

    /**
     * Destroy
     * @param string $order_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $order_id)
    {
        $this->repository->delete($order_id);

        return $this->message(__("Order deleted successfully"), 200);


    }
}
