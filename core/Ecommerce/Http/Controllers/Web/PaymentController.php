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

use App\Http\Controllers\WebController;
use Core\Ecommerce\Repositories\PaymentRepository;
use Inertia\Inertia;
use Illuminate\Http\Request;

class PaymentController extends WebController
{
    /**
     * Repository
     * @var 
     */
    private $repository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        parent::__construct();
        $this->repository = $paymentRepository;

    }

    /**
     * Index
     * @param \Illuminate\Http\Request $request
     * @return  \Inertia\Response
     */
    public function index(Request $request)
    {
        return Inertia::render(
            'Core/Ecommerce/Web/Payment/Index',
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
     * @param Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'payment_method' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (is_null(billing_get_method($value))) {
                        $fail(__("The payment method is not valid"));
                    }
                }
            ],
            'delivery' => ['required', 'exists:delivery_addresses,id'],
            'orders' => ['required', 'array'],
            'orders.*.id' => ['exists:orders,id'],
            'orders.*.product_id' => ['exists:products,id'],
            'orders.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        return $this->repository->create($request->toArray());
    }

    /**
     * Destroy
     * @param string $order_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $order_id)
    {

    }

}
