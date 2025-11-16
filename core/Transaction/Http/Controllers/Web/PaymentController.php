<?php

namespace Core\Transaction\Http\Controllers\Web;

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


use Core\Transaction\Model\Refund;
use Core\Transaction\Transformer\User\ServiceTransformer;
use Core\User\Services\ServiceService;
use Core\User\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    /**
     * Repository
     * @var
     */
    public $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    /**
     * Get the billing period
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function billingPeriod()
    {
        return $this->data(['data' => billing_periods()]);
    }

    /**
     * show the status of payments
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function paymentStatus()
    {
        return $this->data(['data' => billing_statuses()]);
    }

    /**
     * show the currencies
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function currencies()
    {
        return $this->data(['data' => billing_currencies()]);
    }

    /**
     * methods
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function methods()
    {
        return $this->data(['data' => billing_methods()]);
    }

    /**
     * show the all services
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function services(Request $request)
    {
        $data = $this->serviceService->searchForGuest($request)->get();

        return $this->showAll($data, ServiceTransformer::class);
    }

    /**
     * List status for refund or appeal
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function listRefundStatus()
    {
        return $this->data(['data' => Refund::statuses()]);
    }

    /**
     * Payment types
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function paymentTypes()
    {
        return $this->data(['data' => billings_types()]);
    }
}
