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


use Core\Transaction\Model\Refund;
use Core\Transaction\Transformer\User\ServiceTransformer;
use Core\User\Services\ServiceService;
use Core\User\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    /**
     * Repository
     * @var
     */
    public $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    /**
     * Get the billing period
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function billingPeriod()
    {
        return $this->data(['data' => billing_periods()]);
    }

    /**
     * show the status of payments
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function paymentStatus()
    {
        return $this->data(['data' => billing_statuses()]);
    }

    /**
     * show the currencies
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function currencies()
    {
        return $this->data(['data' => billing_currencies()]);
    }

    /**
     * methods
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function methods()
    {
        return $this->data(['data' => billing_methods()]);
    }

    /**
     * show the all services
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function services(Request $request)
    {
        $data = $this->serviceService->searchForGuest($request)->get();

        return $this->showAll($data, ServiceTransformer::class);
    }

    /**
     * List status for refund or appeal
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function listRefundStatus()
    {
        return $this->data(['data' => Refund::statuses()]);
    }

    /**
     * Payment types
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function paymentTypes()
    {
        return $this->data(['data' => billings_types()]);
    }
}
