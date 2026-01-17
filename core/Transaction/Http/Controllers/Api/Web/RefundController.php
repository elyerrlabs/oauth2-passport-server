<?php

namespace Core\Transaction\Http\Controllers\Api\Web;

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
