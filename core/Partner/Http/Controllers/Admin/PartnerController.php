<?php

namespace Core\Partner\Http\Controllers\Admin;

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

use Inertia\Inertia;
use Core\Partner\Services\PartnerService;
use Core\Partner\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use Core\Partner\Transformer\UserTransformer;

class PartnerController extends WebController
{
    /**
     * Repository
     * @var PartnerService
     */
    public $partnerService;

    /**
     * Construct
     * @param \Core\Partner\Services\PartnerService $partnerService
     */
    public function __construct(PartnerService $partnerService)
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator:partner:full,reseller:partner:view')->only('index');
        $this->middleware('userCanAny:administrator:partner:full,administrator:partner:update')->only('update');
        $this->partnerService = $partnerService;
    }

    /**
     * partner
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request)
    {
        $page = $request->filled('per_page') ? $request->per_page : 15;

        $partners = $this->partnerService->listPartners($request)->paginate($page);
        
        return Inertia::render("Admin/Users/Index", [
            'data' => fractal($partners, UserTransformer::class)->toArray(),
            'routes' => [
                'partners' => route('partner.admin.partner.index')
            ],
            "menus" => resolveInertiaRoutes(config('menus.admin_routes'))
        ]);
    }

    /**
     * Update commission rate
     * @param \Illuminate\Http\Request $request
     * @param \Core\Partner\Model\Partner $partner
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'commission_rate' => [
                'required',
                function ($attribute, $value, $fail) {

                    $value = filter_var($value, FILTER_VALIDATE_FLOAT);

                    if (!$value) {
                        $fail(__("The value is incorrect, please fixed and try again"));
                    }
                }
            ]
        ]);

        if ($request->filled("commission_rate")) {
            $user = $this->partnerService->updateCommissionRate($user->id, $request->commission_rate);
        }

        return redirect()->back();
    }
}
