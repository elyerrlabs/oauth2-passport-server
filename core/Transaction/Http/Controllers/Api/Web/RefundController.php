<?php

namespace Core\Transaction\Http\Controllers\Api\Web;

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
use Core\Transaction\Model\Refund;
use Core\Transaction\Services\RefundService;
use Core\Transaction\Http\Requests\RefundStoreRequest;
use Core\Transaction\Transformer\User\UserRefundTransformer;
use Illuminate\Http\Request;

class RefundController extends ApiController
{
    /**
     * Repository
     * @var RefundService
     */
    private $refundService;

    /**
     * Refund service
     * @param \Core\Transaction\Services\RefundService $refundService
     */
    public function __construct(RefundService $refundService)
    {
        parent::__construct();
        $this->refundService = $refundService;
    }

    /**
     * Show the all refund request for the user
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = $this->refundService->searchForUser($request);

        return $this->showAllByBuilder($query, UserRefundTransformer::class);
    }

    /**
     * Add a new refund request
     * @param \Core\Transaction\Http\Requests\RefundStoreRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(RefundStoreRequest $request)
    {
        // Add current user as customer id
        $request->merge([
            'user_id' => auth()->user()->id
        ]);

        $model = $this->refundService->createForUser($request->toArray());

        return $this->showOne($model, UserRefundTransformer::class, 201);
    }

    /**
     * Cancel operation
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function cancel(string $id)
    {
        $this->refundService->cancel($id);

        return $this->message(__("Refund has been cancelled successfully"), 200);
    }
}
