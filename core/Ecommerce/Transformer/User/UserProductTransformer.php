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

use Core\Ecommerce\Model\Product;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;
use Core\Ecommerce\Transformer\User\UserFileTransformer;
use Core\Ecommerce\Transformer\User\UserCategoryTransformer;
use Core\Ecommerce\Transformer\User\UserProductTagTransformer;
use Core\Ecommerce\Transformer\User\UserProductAttributeTransformer;

class UserProductTransformer extends TransformerAbstract
{
    use Asset;

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
    public function transform(Product $product)
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'short_description' => $product->short_description,
            'description' => $product->description,
            'specification' => $product->specification,
            'symbol' => getCurrencySymbol($product->price->currency),
            'stock' => filter_var($product->stock, FILTER_VALIDATE_INT),
            'currency' => $product->price->currency,
            'format_price' => $this->formatMoney($product->price->amount),
            'public' => $product->public ? true : false,
            'category' => fractal($product->category, UserCategoryTransformer::class)->toArray()['data'],
            'images' => $product->getImages($product->files, UserFileTransformer::class),
            'tags' => $product->getTags($product->tags, UserProductTagTransformer::class),
            'attributes' => $product->getAttrCollection($product->attributes, UserProductAttributeTransformer::class),
            'published' => $product->published ? true : false,
            'featured' => $product->featured ? true : false,
            'links' => [
                'show' => route('api.ecommerce.products.show', [
                    'category' => $product->category->slug,
                    'product' => $product->slug
                ]),
            ],
        ];
    }

    /**
     * Return the original attribute
     * @param mixed $index
     * @return string|null
     */
    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'name',
            'slug',
            'stock',
            'price'
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
