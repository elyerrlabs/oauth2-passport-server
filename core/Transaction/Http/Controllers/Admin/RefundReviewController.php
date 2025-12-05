<?php

namespace Core\Transaction\Http\Controllers\Admin;

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
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Core\Transaction\Transformer\Admin\UserTransformer;
use Core\Transaction\Http\Requests\RefundUpdateRequest;
use Core\Transaction\Services\UserService;
use Core\Transaction\Transformer\Admin\RefundReviewTransformer;
use Illuminate\Http\Request;
use Core\Transaction\Model\Refund;
use Core\Transaction\Services\RefundService;
use Inertia\Inertia;

class RefundReviewController extends WebController
{
    /**
     * Refund Service
     * @var RefundService
     */
    private $refundService;


    /**
     * @var UserService
     */
    private $userService;

    /**
     * Construct
     * @param RefundService $refundService
     */
    public function __construct(RefundService $refundService)
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator:refunds:full, administrator:refunds:review');
        $this->refundService = $refundService;
    }

    /**
     * Index
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $page = $request->filled('per_page') ? $request->per_page : 15;

        $request->merge([
            'assigned_to' => auth()->user()->id
        ]);

        $data = $this->refundService->search($request)->paginate($page);

        return Inertia::render(
            'Core/Transaction/Admin/Review/Index',
            [
                'data' => $this->transformCollection($data, RefundReviewTransformer::class),
                'routes' => [
                    'index' => route('transaction.admin.refunds.review.index')
                ],
                "menus" => resolveInertiaRoutes(config('menus.transaction_routes')),
            ]
        );
    }

    /**
     * Show refund
     * @param Refund $refund
     * @return Response
     */
    public function show(string $id)
    {
        $data = $this->refundService->find($id);

        return Inertia::render(
            "Core/Transaction/Admin/Review/Details",
            [
                'data' => $this->transform($data, RefundReviewTransformer::class),
                'routes' => [
                    'index' => route('transaction.admin.refunds.index'),
                ],
                "menus" => resolveInertiaRoutes(config('menus.transaction_routes')),
            ]
        );
    }

    /**
     * Update refund
     * @param Request $request
     * @param Refund $refund
     * @return RedirectResponse
     */
    public function update(RefundUpdateRequest $request, Refund $refund)
    {
        $this->refundService->updateStatus($refund->id, $request->toArray());

        return redirect()->back();
    }

    /**
     * Assign to
     * @param Request $request
     * @return RedirectResponse
     */
    public function assignTo(Request $request, string $id)
    {
        $this->validate($request, [
            'assigned_to' => 'exists:users,id'
        ]);

        $this->refundService->assignTo($id, $request->assigned_to);

        return redirect()->back();
    }


    /**
     * List users for refund assignment
     * @param Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function listUsersForRefundAssignment(Request $request)
    {
        $query = $this->userService->listUsersForRefundAssignment($request);

        return $this->showAllByBuilder($query, UserTransformer::class);
    }
}
