<?php

namespace Core\Ecommerce\Transformer\Admin;

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
use Core\Ecommerce\Transformer\Admin\CategoryTransformer;

class ProductTransformer extends TransformerAbstract
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
            'published' => $product->published ? true : false,
            'featured' => $product->featured ? true : false,
            'stock' => $product->variants->sum('stock'),
            'price' => $product->variants[0]->price->amount,
            'format_price' => $this->formatMoney($product->variants[0]->price->amount),
            'currency' => getCurrencySymbol($product->variants[0]->price->currency),
            'short_description' => $product->short_description,
            'description' => $product->description,
            'specification' => $product->specification,
            'category' => fractal($product->category, CategoryTransformer::class)->toArray()['data'],
            'images' => $product->getImages($product->files),
            'tags' => $product->getTags($product->tags),
            'attributes' => $product->getAttr($product->attributes),
            'variants' => fractal($product->variants, VariantTransformer::class)->toArray()['data'],
            'links' => [
                'index' => route('ecommerce.admin.products.index'),
                'store' => route('ecommerce.admin.products.store'),
                'edit' => route('ecommerce.admin.products.edit', ['product' => $product->id]),
                'destroy' => route('ecommerce.admin.products.destroy', ['product' => $product->id]),
            ]
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
