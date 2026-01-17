<?php

namespace Core\Transaction\Http\Controllers\Admin;


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

use App\Http\Controllers\WebController;
use Core\Transaction\Transformer\Admin\UserTransformer;
use Core\Transaction\Http\Requests\RefundUpdateRequest;
use Core\Transaction\Services\UserService;
use Core\Transaction\Transformer\Admin\RefundTransformer;
use Illuminate\Http\Request;
use Core\Transaction\Model\Refund;
use Core\Transaction\Services\RefundService;
use Inertia\Inertia;

class RefundController extends WebController
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
        $this->middleware('userCanAny:administrator:refunds:full, administrator:refunds:view')->only('index', 'show');
        $this->middleware('userCanAny:administrator:refunds:full, administrator:refunds:update')->only('update');
        $this->middleware('userCanAny:administrator:refunds:full, administrator:refunds:assign')->only('assignTo', 'listUsersForRefundAssignment');
        $this->refundService = $refundService;
        $this->userService = app(UserService::class);
    }

    /**
     * Index
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $page = $request->filled('per_page') ? $request->per_page : 15;

        $data = $this->refundService->search($request)->paginate($page);

        return Inertia::render(
            'Admin/Refund/Index',
            [
                'data' => $this->transformCollection($data, RefundTransformer::class),
                'routes' => [
                    'index' => route('transaction.admin.refunds.index')
                ],
                "menus" => resolveInertiaRoutes(config('menus.transaction_routes')),
            ]
        );
    }

    /**
     * Show refund
     * @param Refund $refund
     * @return \Inertia\Response
     */
    public function show(string $id)
    {
        $data = $this->refundService->find($id);

        return Inertia::render(
            "Admin/Refund/Details",
            [
                'data' => $this->transform($data, RefundTransformer::class),
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(RefundUpdateRequest $request, Refund $refund)
    {
        $this->refundService->updateStatus($refund->id, $request->toArray());

        return redirect()->back();
    }

    /**
     * Assign to
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
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
