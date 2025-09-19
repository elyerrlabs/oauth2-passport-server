<?php

namespace Core\Ecommerce\Http\Controllers\Api\Web;

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

use App\Http\Controllers\WebController;
use Core\Ecommerce\Repositories\AttributeRepository;
use Core\Ecommerce\Transformer\User\UserAttributeTransformer;
use Illuminate\Http\Request;

class FilterController extends WebController
{

    private $repository;

    public function __construct(AttributeRepository $attributeRepository)
    {
        $this->repository = $attributeRepository;
    }

    /**
     * List the attributes
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if (!$request->filled('per_page')) {
            $request->merge(['per_page' => 150]);
        }

        $query = $this->repository->listAttributes();

        return $this->showAll($query, UserAttributeTransformer::class);
    }
}
