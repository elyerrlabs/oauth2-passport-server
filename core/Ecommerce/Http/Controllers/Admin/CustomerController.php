<?php

namespace Core\Ecommerce\Http\Controllers\Admin;

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

use Core\Ecommerce\Transformer\Admin\UserTransformer;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use Core\Ecommerce\Repositories\CheckoutRepository;

class CustomerController extends WebController
{

    private $repository;


    public function __construct(CheckoutRepository $checkoutRepository)
    {
        $this->middleware("userCanAny:administrator:ecommerce:full,administrator:ecommerce:view");
        $this->repository = $checkoutRepository;
    }

    /**
     * Customers
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {

            $query = $this->repository->listCustomers($request);

            return $this->showAllByBuilder($query, UserTransformer::class);
        }

        return Inertia::render(
            "Core/Ecommerce/Admin/Order/Customer",
            [
                'routes' => [
                    'customers' => route("ecommerce.admin.orders.customers")
                ],
                'ecommerce_menus' => resolveInertiaRoutes(config('menus.ecommerce_menus'))
            ]
        )->rootView('ecommerce');
    }
}