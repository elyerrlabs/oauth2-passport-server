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

use App\Http\Controllers\ApiController;
use Core\Ecommerce\Repositories\PaymentRepository;
use Core\Transaction\Transformer\User\UserCheckoutTransformer;
use Inertia\Inertia;
use Illuminate\Http\Request;

class CheckoutController extends ApiController
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
    {   //
        $query = $this->repository->search($request);

        return $this->showAllByBuilder($query, UserCheckoutTransformer::class);
    }
}
