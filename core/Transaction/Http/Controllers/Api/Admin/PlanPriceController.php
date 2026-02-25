<?php

namespace Core\Transaction\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiController;
use Core\Transaction\Services\PlanService;

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
final class PlanPriceController extends ApiController
{
    public function __construct(protected PlanService $planService)
    {
        parent::__construct();
        $this->middleware('scope:administrator:plan:full,administrator:plan:destroy')->only('destroy');
    }

    /**
     * remove prices
     * @param string $plan_id
     * @param string $price_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $plan_id, string $price_id)
    {
        $this->planService->deletePrice($plan_id, $price_id);

        return $this->message(__('Plan removed successfully'), 200);
    }
}
