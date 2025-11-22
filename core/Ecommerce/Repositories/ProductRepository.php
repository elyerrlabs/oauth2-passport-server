<?php

namespace Core\Ecommerce\Repositories;

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

use Core\Ecommerce\Model\Product;

final class ProductRepository
{

    /**
     * Product
     * @var Product
     */
    public $model;

    /**
     * Summary of storage
     * @var
     */
    private $storage;

    /**
     * Category repository
     * @var CategoryRepository
     */
    private $category_repository;

    public function __construct(Product $product)
    {
        $this->model = $product;
        $this->storage = "products";
    }

    /**
     * The storage file
     * @return string
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * Query
     * @return \Illuminate\Database\Eloquent\Builder<Product>
     */
    public function query()
    {
        $query = $this->model->query();

        $query->with([
            'category',
            'files',
            'tags',
            'attributes',
            'variants',
            'variants.price',
            'children'
        ]);

        return $query;
    }


    /**
     * Search specific resource
     * @param string $id
     * @return Product|null
     */
    public function find(string $id)
    {
        return $this->query()->where('id', $id)->first();
    }

    /**
     * Find by code
     * @param string $code
     * @return Product|TValue|null
     */
    public function findByCode(string $code)
    {
        return $this->query()->where('code', $code)->first();
    }

    /**
     * Find by name
     * @param string $name
     * @return object|\Illuminate\Database\Eloquent\Builder<Product>|\Illuminate\Database\Eloquent\Model|null
     */
    public function findByName(string $name)
    {
        return $this->query()
            ->whereRaw(
                "LOWER(name) = ?",
                [strtolower($name)]
            )->first();
    }

    /**
     * Create new resource
     * @param array $data
     * @return Product|TModel|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update product
     * @param string $id
     * @param array $data
     * @return Product|null
     */
    public function update(string $id, array $data)
    {
        $product = $this->find($id);

        $product->update($data);

        return $product;
    }
}
