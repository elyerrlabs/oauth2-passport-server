<?php

namespace Core\Ecommerce\Http\Controllers\Api\Web;

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

use Core\Ecommerce\Services\OrderService;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Core\Ecommerce\Http\Requests\Order\StoreRequest;
use Core\Ecommerce\Transformer\User\UserOrderTransformer;

class OrderController extends ApiController
{
    /**
     * Repository
     * @var 
     */
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        parent::__construct();
        $this->orderService = $orderService;
    }

    /**
     * Show the all resource
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = $this->orderService->searchForUser($request);

        return $this->showAllByBuilder($query, UserOrderTransformer::class);
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

        $model = $this->orderService->create($request->toArray());

        return $this->showOne($model, UserOrderTransformer::class, 201);
    }

    /**
     * Destroy
     * @param string $order_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $order_id)
    {
        $this->orderService->delete($order_id);

        return $this->message(__("Order deleted successfully"), 200);
    }
}
