<?php

namespace Core\Ecommerce\Http\Controllers\Api\Admin;

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

use Core\Ecommerce\Services\CheckoutService;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Core\Ecommerce\Transformer\Admin\CheckoutTransformer;

class OrderController extends ApiController
{
    /**
     * Checkout Service
     * @var CheckoutService
     */
    private $checkoutService;


    public function __construct(CheckoutService $checkoutService)
    {
        parent::__construct();
        $this->middleware("scope:administrator:ecommerce:full,administrator:ecommerce:view");
        $this->checkoutService = $checkoutService;
    }

    /**
     * Show orders
     * @param \Illuminate\Http\Request $request
     */
    public function complete(Request $request)
    {
        $query = $this->checkoutService->search($request);

        return $this->showAllByBuilder($query, CheckoutTransformer::class);
    }


    /**
     * Show orders
     * @param \Illuminate\Http\Request $request
     */
    public function pending(Request $request)
    {
        $query = $this->checkoutService->search($request);

        return $this->showAllByBuilder($query, CheckoutTransformer::class);
    }
}