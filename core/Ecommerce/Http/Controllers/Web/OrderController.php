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

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use Core\Ecommerce\Repositories\OrderRepository;

class OrderController extends WebController
{
    /**
     * Repository
     * @var
     */
    private $repository;

    public function __construct(OrderRepository $orderRepository)
    {
        parent::__construct();
        $this->repository = $orderRepository;
    }

    /**
     * Index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request)
    {
        return Inertia::render(
            'Core/Ecommerce/Web/Orders',
            [
                'routes' => [
                    'orders_api' => route('api.ecommerce.orders.index'),
                    'search' => route('ecommerce.search'),
                    'categories_api' => route('api.ecommerce.categories.index'),
                    'payment_api' => route('api.ecommerce.payments.store'),
                ]
            ]
        )->rootView('ecommerce');
    }
}
