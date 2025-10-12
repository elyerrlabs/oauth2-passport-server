<?php

namespace Core\Partner\Http\Controllers\Admin;

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

use Inertia\Inertia;
use Core\Partner\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use Core\Partner\Transformer\UserTransformer;
use Core\Partner\Repositories\PartnerRepository;

class PartnerController extends WebController
{
    /**
     * Repository
     * @var
     */
    public $repository;

    public function __construct(PartnerRepository $partnerRepository)
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator:partner:full,reseller:partner:view')->only('index');
        $this->middleware('userCanAny:administrator:partner:full,administrator:partner:update')->only('update');
        $this->repository = $partnerRepository;
    }

    /**
     * partner
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = $this->repository->listPartners($request);

            return $this->showAllByBuilder($query, UserTransformer::class);
        }

        return Inertia::render("Core/Partner/Admin/Users/Index", [
            'routes' => [
                'partners' => route('partner.admin.partner.index')
            ],
            "partner_routes" => resolveInertiaRoutes(config('menus.partner_routes'))
        ])->rootView('system');
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
                        $fail("The value is incorrect, please fixed and try again");
                    }
                }
            ]
        ]);

        if ($request->filled("commission_rate")) {
            $user = $this->repository->updateCommissionRate(
                $user->id,
                $request->commission_rate,
            );
        }

        return $this->message("Commission rate updated successfully");
    }
}
