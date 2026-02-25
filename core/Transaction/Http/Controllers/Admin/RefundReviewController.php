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
use Inertia\Response;
use Illuminate\Http\Request;
use Core\Transaction\Model\Refund;
use Inertia\Inertia;

class RefundReviewController extends WebController
{

    /**
     * Construct
     * 
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator:refunds:full, administrator:refunds:review');
    }

    /**
     * Index
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        return Inertia::render(
            'Admin/Review/Index',
            [
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
        return Inertia::render(
            "Admin/Review/Details",
            [
                "menus" => resolveInertiaRoutes(config('menus.transaction_routes')),
            ]
        );
    }
}
