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
 
use App\Models\Common\Attribute;
use Core\Ecommerce\Model\Product;
use League\Fractal\TransformerAbstract;

class ProductAttributeTransformer extends TransformerAbstract
{

    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
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
    public function transform(Attribute $attribute)
    {
        return [
            'name' => ucfirst($attribute->name),
            'slug' => strtolower($attribute->slug),
            'type' => strtolower($attribute->type),
            'value' => $attribute->value,
            'widget' => strtolower($attribute->widget),
            'multiple' => $attribute->multiple ? true : false,
            'stock' => $attribute->pivot->stock,
            //'unit_id'
            'links' => [
                'destroy' => route('api.ecommerce.admin.products.attributes.destroy', [
                    'product' => $this->product->id,
                    'attribute' => $attribute->id
                ])
            ],
        ];
    }
}
