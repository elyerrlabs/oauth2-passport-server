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

use App\Transformers\File\FileTransformer;
use Core\Ecommerce\Model\Product;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;
use Core\Ecommerce\Transformer\Admin\CategoryTransformer;

class ProductChildrenTransformer extends TransformerAbstract
{
    use Asset;

    private $parent;

    public function __construct(Product $product)
    {
        $this->parent = $product;
    }

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
        $variant = count($product->variants) ? $product->variants[0] : null;

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'published' => $product->published ? true : false,
            'featured' => $product->featured ? true : false,
            'stock' => !empty($variant) ? $variant->sum('stock') : 0,
            'price' => !empty($variant) ? $variant->price->amount : 0,
            'format_price' => !empty($variant) ? $this->formatMoney($variant->price->amount) : 0,
            'currency' => !empty($variant) ? $variant->price->currency : '',
            'symbol' => !empty($variant) ? getCurrencySymbol($variant->price->currency) : '',
            'short_description' => $product->short_description,
            'description' => $product->description,
            'specification' => $product->specification,
            'category' => fractal($product->category, CategoryTransformer::class)->toArray()['data'] ?? [],
            'images' => fractal($product->files, new FileTransformer($product->id))->toArray()['data'] ?? [],
            'tags' => fractal($product->tags, new ProductTagTransformer($product))->toArray()['data'] ?? [],
            'attributes' => fractal($product->attributes, new ProductAttributeTransformer($product))->toArray()['data'] ?? [],
            'variants' => fractal($product->variants, VariantTransformer::class)->toArray()['data'] ?? [],
            'links' => [
                'delete' => route('api.ecommerce.admin.products.children.destroy', [
                    'product' => $this->parent->id,
                    'child' => $product->id,
                ])
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
