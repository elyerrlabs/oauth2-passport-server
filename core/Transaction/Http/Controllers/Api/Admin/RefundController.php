<?php

namespace Core\Transaction\Http\Controllers\Api\Admin;

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
use Core\Transaction\Http\Requests\RefundUpdateRequest;
use Core\Transaction\Services\Payment\RefundService;
use Core\Transaction\Transformer\Admin\RefundTransformer;
use Illuminate\Http\Request;
use Core\Transaction\Repositories\RefundRepository;

class RefundController extends ApiController
{
    /**
     * Repository
     * @var RefundService
     */
    private $refundService;

    /**
     * Construct
     * @param \Core\Transaction\Services\Payment\RefundService $refundService
     */
    public function __construct(RefundService $refundService)
    {
        parent::__construct();
        $this->middleware('scope:administrator:transactions:full,administrator:transactions:view')->only('index');
        $this->middleware('scope:administrator:transactions:full,administrator:transactions:update')->only('update');
        $this->refundService = $refundService;
    }


    /**
     * Index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = $this->refundService->search($request);

        return $this->showAllByBuilder($query, RefundTransformer::class);
    }

    /**
     * Update
     * @param \Core\Transaction\Http\Requests\RefundUpdateRequest $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(RefundUpdateRequest $request, string $id)
    {
        $data = $this->refundService->updateStatus($id, $request->toArray());

        return $this->showOne($data, RefundTransformer::class);
    }
}
