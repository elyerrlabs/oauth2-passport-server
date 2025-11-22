<?php

namespace Core\Ecommerce\Model;

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

use App\Models\Master;
use Core\Ecommerce\Model\Variant;
use Core\Ecommerce\Model\Tag;
use Core\Ecommerce\Model\Attribute;
use Core\Ecommerce\Model\File;
use Core\Ecommerce\Model\Category;
use Illuminate\Database\Eloquent\Collection;
use Core\Ecommerce\Transformer\Admin\ProductAttributeTransformer;

class Product extends Master
{
    /**
     * Table name
     * @var string
     */
    protected $table = "products";

    /**
     * Tag to use in category
     * @var string
     */
    public $tag = "ecommerce_product";

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'specification',
        'featured',
        'published',
        'category_id',
        'parent_id'
    ];

    /**
     * Property cats
     * @var array
     */

    public $cats = [
        'featured' => 'boolean',
        'published' => 'boolean'
    ];

    /**
     * Get tag
     * @return string
     */
    public static function getTag()
    {
        return (new static())->tag;
    }


    /**
     * @param mixed $value
     * @return void
     */
    public function setFeaturedAttribute($value)
    {
        $this->attributes['featured'] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Parent
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Product, Product, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function parent()
    {
        return $this->belongsToMany(
            static::class,
            'related_products',
            'children_id',
            'parent_id'
        );
    }

    /**
     * Has children
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Product, Product, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function children()
    {
        return $this->belongsToMany(
            static::class,
            'related_products',
            'parent_id',
            'children_id'
        );
    }

    /**
     *
     * @param mixed $value
     * @return void
     */
    public function setPublishedAttribute($value)
    {
        $this->attributes['published'] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Belongs to category
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Category, Product>
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * Has many files
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<File, Product>
     */
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    /**
     * Attributes
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany<Attribute, Product>
     */
    public function attributes()
    {
        return $this->morphToMany(Attribute::class, 'attributable');
    }

    /**
     * Summary of tags
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany<Tag, Product>
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function getAttrCollection(Collection $collection, $transformer = ProductAttributeTransformer::class)
    {
        $result = $collection
            ->groupBy('name')
            ->map(function ($items, $key) {
                return [
                    'name' => $key,
                    'slug' => $items->first()['slug'],
                    'value' => $items->pluck('value')->all(),
                ];
            })
            ->values();

        return fractal(
            $result,
            new $transformer($this)
        )->toArray()['data'] ?? [];
    }

    /**
     *Variant
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function variants()
    {
        return $this->morphMany(Variant::class, 'variantable');
    }
}
