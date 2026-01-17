<?php

namespace Core\Partner\Http\Controllers\Web;

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


use Core\Partner\Transformer\DataTransformer;
use Core\Partner\Transformer\PartnerTransformer;
use Core\Partner\Transformer\TransactionTransformer;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use Core\Partner\Services\PartnerService;

class PartnerController extends WebController
{

    /**
     * User repository
     * @var PartnerService
     */
    public $partnerService;

    public function __construct(PartnerService $partnerService)
    {
        parent::__construct();
        $this->partnerService = $partnerService;
        $this->middleware("userCanAny:reseller:partner:full,reseller:partner:dashboard")->only('dashboard');
        $this->middleware("userCanAny:reseller:partner:full,reseller:partner:show")->only('show');
        $this->middleware("userCanAny:reseller:partner:full,reseller:partner:create")->only('generate');
        $this->middleware("userCanAny:reseller:partner:full,reseller:partner:view")->only('sales');
    }

    /**
     * Summary of dashboard
     * @param \Illuminate\Http\Request $request
     * @param \ Core\Transaction\Model\Transaction $transaction
     * @return \Inertia\Response | array
     */
    public function dashboard(Request $request)
    {
        $data = $this->partnerService->partnerDashboard($request);

        return Inertia::render("Web/Index", [
            "data" => $data,
            "route" => route("partner.dashboard"),
            "menus" => resolveInertiaRoutes(config('menus.partner_routes'))
        ]);
    }


    /**
     * Show referral link
     * @return \Inertia\Response
     */
    public function show()
    {
        $partner = $this->partnerService->details(auth()->user()->id);

        return Inertia::render("Web/Refer", [
            "data" => fractal($partner, PartnerTransformer::class)->toArray()['data'] ?? [],
            "menus" => resolveInertiaRoutes(config('menus.partner_routes'))
        ]);
    }


    /**
     * Show the all transactions
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser|\Inertia\Response
     */
    public function sales(Request $request)
    {
        $page = $request->filled('per_page') ? $request->get('per_page') : 15;

        $data = $this->partnerService->listLastTransactions($request)->paginate($page);

        return Inertia::render("Web/Sales", [
            "data" => fractal($data, TransactionTransformer::class)->toArray(),
            "route" => route("partner.sales"),
            "menus" => resolveInertiaRoutes(config('menus.partner_routes'))
        ]);
    }
}
