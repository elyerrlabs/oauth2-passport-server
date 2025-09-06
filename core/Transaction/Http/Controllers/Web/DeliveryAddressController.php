<?php

namespace Core\Transaction\Http\Controllers\Web;

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

use Core\Transaction\Http\Requests\DeliveryRequest;
use Core\Transaction\Repositories\DeliveryAddressRepository;
use Core\Transaction\Transformer\User\DeliveryAddressTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;

class DeliveryAddressController extends WebController
{
    private $repository;

    public function __construct(DeliveryAddressRepository $deliveryAddressRepository)
    {
        parent::__construct();
        $this->repository = $deliveryAddressRepository;
    }

    /**
     * index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if (!$request->wantsJson()) {
            abort(__('Page not found'), 404);
        }

        $query = $this->repository->search($request);

        return $this->showAllByBuilder($query, DeliveryAddressTransformer::class);
    }

    /**
     * Update or create a nue resource
     * @param \Core\Transaction\Http\Requests\DeliveryRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(DeliveryRequest $request)
    {
        if ($request->filled('id')) {
            $model = $this->repository->update($request->id, $request->toArray());
            return $this->showOne($model, DeliveryAddressTransformer::class, 200);

        }

        $request->merge([
            'user_id' => auth()->user()->id
        ]);

        $model = $this->repository->create($request->toArray());

        return $this->showOne($model, DeliveryAddressTransformer::class, 201);
    }

    /**
     * delete resource
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $this->repository->delete($id);

        return $this->message(__('Delivery address has been delete successfully'), 200);
    }

}
