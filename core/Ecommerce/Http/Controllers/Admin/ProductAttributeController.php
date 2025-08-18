<?php

namespace Core\Ecommerce\Http\Controllers\Admin;

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
use Core\Ecommerce\Repositories\ProductRepository; 

final class ProductAttributeController extends WebController
{

    /**
     *  repository
     * @var ProductRepository
     */
    private $repository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->repository = $productRepository;
    }

    /**
     * Delete tag from the model
     * @param string $product_id
     * @param string $tag_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $product_id, string $tag_id)
    {
        $model = $this->repository->deleteAttribute($product_id, $tag_id);

        return $this->showOne($model);
    }
}
