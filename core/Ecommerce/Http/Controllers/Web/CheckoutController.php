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

use Core\Ecommerce\Services\CheckoutService;
use Core\Ecommerce\Transformer\User\UserCheckoutTransformer;
use Inertia\Inertia;
use Core\Ecommerce\Services\RouteService;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;

class CheckoutController extends WebController
{
    /**
     * Checkout service
     * @var CheckoutService
     */
    private $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        parent::__construct();
        $this->checkoutService = $checkoutService;
    }

    /**
     * Index
     * @param \Illuminate\Http\Request $request
     * @return  \Inertia\Response
     */
    public function index(Request $request)
    {
        return Inertia::render(
            'Core/Ecommerce/Web/Checkout/Index',
            [
                'routes' => [
                    'search' => route('ecommerce.search'),
                    'dashboard' => route('ecommerce.dashboard'),
                ],
                'api' => RouteService::api(),
            ]
        )->rootView('ecommerce');
    }

    /**
     * Refund product
     * @param Request $request
     * @return \Inertia\Response
     */
    public function show(string $id)
    {
        $data = $this->checkoutService->details($id);

        return Inertia::render(
            'Core/Ecommerce/Web/Checkout/Details',
            [
                'data' => $this->transform($data, UserCheckoutTransformer::class),
                'routes' => [
                    'show' => route('ecommerce.checkouts.show', ['checkout' => $id]),
                    'search' => route('ecommerce.search'),
                    'dashboard' => route('ecommerce.dashboard'),
                ],
                'api' => RouteService::api(),
            ]
        )->rootView('ecommerce');
    }
}
