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


use Core\Transaction\Services\TransactionService;
use Core\Transaction\Transformer\Admin\TransactionTransformer;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use Core\Transaction\Repositories\TransactionRepository;

class TransactionManagerController extends WebController
{
    /**
     * Repository
     * @var TransactionService
     */
    public $transactionService;

    /**
     * Construct
     * @param \Core\Transaction\Services\TransactionService $transactionService
     */
    public function __construct(TransactionService $transactionService)
    {
        parent::__construct();
        $this->transactionService = $transactionService;
        $this->middleware('userCanAny:administrator:transactions:full,administrator:transactions:view')->only('index');
    }

    /**
     * Show the resources
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser|\Inertia\Response
     */
    public function index(Request $request)
    {
        // Apply pagination
        $per_page = $request->filled("per_page") ? $request->per_page : 15;

        // Retrieve data using the transaction service
        $data = $this->transactionService->search($request)->paginate($per_page);

        return Inertia::render(
            "Admin/Transaction/Index",
            [
                "data" => fractal($data, TransactionTransformer::class)->toArray() ?? [],
                "route" => route('transaction.admin.transactions.index'),
                "menus" => resolveInertiaRoutes(config('menus.transaction_routes')),
            ]
        );
    }
}
