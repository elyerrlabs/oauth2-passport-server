<?php

namespace Core\Ecommerce\Transformer\User;

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


use App\Models\Common\Category;
use League\Fractal\TransformerAbstract;
use Core\Ecommerce\Transformer\User\UserFileTransformer;

class UserCategoryChildrenTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'description' => $category->description,
            'featured' => $category->featured ? true : false,
            'published' => $category->published ? true : false,
            'icon' => fractal($category->icon, UserIconTransformer::class)->toArray()['data'] ?? [],
            'images' => fractal($category->files, UserFileTransformer::class)->toArray()['data'] ?? [],
            'children' => fractal($category->children, UserCategoryChildrenTransformer::class)->toArray()['data'] ?? [],
            'links' => [
                'index' => route('ecommerce.category', [
                    'category' => $category->slug
                ]),
                'index_api' => route('api.ecommerce.category.show', [
                    'category' => $category->slug
                ]),
            ],
        ];
    }
}
