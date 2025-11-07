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
use Core\Ecommerce\Transformer\Admin\ProductTransformer;

class ProductChildrenController extends WebController
{
    /**
     * ProductRepository
     * @var ProductRepository
     */
    private $repository;

    /**
     * Summary of __construct
     * @param  ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        parent::__construct();
        $this->repository = $productRepository;
        $this->middleware('userCanAny:administrator:ecommerce:full, administrator:ecommerce:delete')->only('destroy');
    }

    /**
     * Unlink children to the product parent
     * @param string $product_id
     * @param string $children_id
     * @return \Core\Ecommerce\Model\Product|null
     */
    public function destroy(string $product_id, string $children_id)
    {
        $this->repository->deleteProductRelated($product_id, $children_id);
        return $this->message(__('Child deleted successfully'));
    }
}
