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
use Core\Transaction\Http\Requests\DeliveryRequest;
use Core\Transaction\Transformer\User\DeliveryAddressTransformer;
use Illuminate\Http\Request;
use Core\Transaction\Services\DeliveryAddressService;

class DeliveryAddressController extends ApiController
{

    public function __construct(protected DeliveryAddressService $deliveryAddressService)
    {
        parent::__construct();
    }

    /**
     * index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = $this->deliveryAddressService->search($request);

        return $this->showAllByBuilder($query, DeliveryAddressTransformer::class);
    }

    /**
     * Update or create a nue resource
     * @param \Core\Transaction\Http\Requests\DeliveryRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(DeliveryRequest $request)
    {
        $model = $this->deliveryAddressService->create($request->toArray());

        return $this->showOne($model, DeliveryAddressTransformer::class, 201);
    }

    /**
     * Update
     * @param DeliveryRequest $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(DeliveryRequest $request, string $id)
    {
        $model = $this->deliveryAddressService->update($id, $request->toArray());

        return $this->showOne($model, DeliveryAddressTransformer::class, 200);
    }

    /**
     * delete resource
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $model = $this->deliveryAddressService->delete($id);

        return $this->showOne($model, DeliveryAddressTransformer::class, 200);
    }
}
