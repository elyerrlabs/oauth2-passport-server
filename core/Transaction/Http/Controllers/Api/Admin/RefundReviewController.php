<?php

namespace Core\Transaction\Http\Controllers\Api\Admin;

/**
 * OAuth2 Passport Server — a centralized, modular authorization server
 * implementing OAuth 2.0 and OpenID Connect specifications.
 *
 * Copyright (c) 2026 Elvis Yerel Roman Concha
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * Author: Elvis Yerel Roman Concha
 * Contact: yerel9212@yahoo.es
 *
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

use App\Http\Controllers\ApiController;
use App\Http\Controllers\WebController;
use Inertia\Response;
use Core\Transaction\Transformer\Admin\UserTransformer;
use Core\Transaction\Http\Requests\RefundUpdateRequest;
use Core\Transaction\Services\UserService;
use Core\Transaction\Transformer\Admin\RefundReviewTransformer;
use Illuminate\Http\Request;
use Core\Transaction\Model\Refund;
use Core\Transaction\Services\RefundService;

class RefundReviewController extends ApiController
{
    /**
     * Construct
     * @param RefundService $refundService
     * @param UserService $userService
     */
    public function __construct(protected RefundService $refundService, protected UserService $userService)
    {
        parent::__construct();
        $this->middleware('scope:administrator:refunds:full, administrator:refunds:review');
    }

    /**
     * Index
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $data = $this->refundService->search($request);

        return  $this->showAllByBuilder($data, RefundReviewTransformer::class);
    }

    /**
     * show
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $data = $this->refundService->find($id);

        return $this->showOne($data, RefundReviewTransformer::class);
    }

    /**
     * update
     * @param RefundUpdateRequest $request
     * @param Refund $refund
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(RefundUpdateRequest $request, Refund $refund)
    {
        $data =  $this->refundService->updateStatus($refund->id, $request->toArray());

        return $this->showOne($data, RefundReviewTransformer::class);
    }

    /**
     * assign To
     * @param Request $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assignTo(Request $request, string $id)
    {
        $data = $this->refundService->assignTo($id, $request->assigned_to);

        return $this->showOne($data, RefundReviewTransformer::class);
    }

    /**
     * 
     * @param Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function listUsersForRefundAssignment(Request $request)
    {
        $query = $this->userService->listUsersForRefundAssignment($request);

        return $this->showAllByBuilder($query, UserTransformer::class);
    }
}
