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
use Core\Ecommerce\Model\Category;
use Illuminate\Support\Facades\Storage;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Core\Ecommerce\Transformer\Admin\CategoryTransformer;

class CategoryRepository
{
    /**
     * Model
     * @var Category
     */
    private $model;

    /**
     * Storage
     * @var string
     */
    private $storage;

    /**
     * Construct
     * @param \Core\Ecommerce\Model\Category $category
     */
    public function __construct(Category $category)
    {
        $this->model = $category;
        $this->storage = "category";
    }

    /**
     * Get the storage name
     * @return string
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * Query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<Category>
     */
    public function query()
    {
        $query = $this->model->query();

        // Only product categories
        $query->where('tag', Product::getTag());

        $query->with([
            'parent',
            'children',
            'icon',
            'files',
        ]);

        return $query;
    }

    /**
     * Create new resource
     * @param array $data
     * @return Category|TModel|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Search specific resource
     * @param string $id
     * @return TModel|TValue|null
     */
    public function find(string $id)
    {
        return $this->query()->where('id', $id)->first();
    }

    /**
     * Update category
     * @param string $id
     * @param array $data
     * @return Category|TModel|\Illuminate\Database\Eloquent\Model
     */
    public function update(string $id, array $data)
    {
        $model = $this->find($id);

        $model->update($data);

        return $model;
    }
}
