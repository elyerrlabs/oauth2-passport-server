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
use Core\Transaction\Services\TransactionService;
use Core\Transaction\Transformer\Admin\TransactionTransformer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionManagerController extends WebController
{

    /**
     * Construct
     * 
     */
    public function __construct(protected TransactionService $transactionService)
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator:transactions:full,administrator:transactions:dashboard')->only('dashboard');
        $this->middleware('userCanAny:administrator:transactions:full,administrator:transactions:view')->only('index', 'show');
    }

    /**
     * Dashboard
     * @param Request $request
     * @return \Inertia\Response
     */
    public function dashboard(Request $request)
    {
        $billing_statuses = billing_statuses();

        return Inertia::render("Admin/Dashboard/Index", [
            "data" => $this->transactionService->dashboard($request),
            "billing_statuses" => $billing_statuses,
            "route" => route("transaction.admin.dashboard"),
            "menus" => resolveInertiaRoutes(config('menus.transaction_routes')),
        ]);
    }

    /**
     * Show the resources
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser|\Inertia\Response
     */
    public function index(Request $request)
    {
        $data = $this->transactionService->search($request);
        $billing_types = billings_types();
        $billing_statuses = billing_statuses();

        return Inertia::render(
            "Admin/Transaction/Index",
            [
                "menus" => resolveInertiaRoutes(config('menus.transaction_routes')),
                "data" => $this->transformCollection($data->paginate($request->input('per_page', 25)), TransactionTransformer::class),
                "billing_types" => $billing_types,
                "billing_statuses" => $billing_statuses,
                "routes" => [
                    'transactions' => route('transaction.admin.transactions.index'),
                ],
            ]
        );
    }

    /**
     * show
     * @param mixed $id
     * @return \Inertia\Response
     */
    public function show($id)
    {
        $transaction = $this->transactionService->findById($id);

        return Inertia::render("Admin/Transaction/Detail", [
            "data" => $this->transform($transaction, TransactionTransformer::class),
            "menus" => resolveInertiaRoutes(config('menus.transaction_routes')),
        ]);
    }
}
