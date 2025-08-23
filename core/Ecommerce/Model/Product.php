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
use App\Models\Common\Tag;
use App\Models\Common\File;
use App\Models\Common\Category;
use App\Models\Common\Attribute;
use App\Models\Common\Price;
use App\Transformers\File\FileTransformer;
use Illuminate\Database\Eloquent\Collection;
use Core\Ecommerce\Transformer\Admin\ProductTagTransformer; 
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
        'stock',
        'featured',
        'published',
        'category_id',
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
     * set stock
     * @param mixed $value
     * @return void
     */
    public function setStock($value)
    {
        $value = str_replace([',', '.'], '', $value);
        $this->attributes['stock'] = filter_var($value, FILTER_VALIDATE_INT);
    }

    /**
     * 
     * @param mixed $value
     * @return void
     */
    public function setFeaturedAttribute($value)
    {
        $this->attributes['featured'] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
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
     * Retrieve the all images
     * @param \Illuminate\Database\Eloquent\Collection $collection
     * @param mixed $transformer
     */
    public function getImages(Collection $collection, $transformer = FileTransformer::class)
    {
        return fractal($collection, $transformer)->toArray()['data'] ?? [];
    }

    /**
     * Attributes
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany<Attribute, Product>
     */
    public function attributes()
    {
        return $this->morphToMany(Attribute::class, 'attributable')->withPivot('stock');
    }

    /**
     * Summary of tags
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany<Tag, Product>
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Retrieve the tags
     * @param \Illuminate\Database\Eloquent\Collection $collection
     * @param mixed $transformer
     */
    public function getTags(Collection $collection, $transformer = ProductTagTransformer::class)
    {
        return fractal(
            $collection,
            new $transformer($this)
        )->toArray()['data'] ?? [];
    }


    /**
     * Retrieve the tags
     * @param \Illuminate\Database\Eloquent\Collection $collection
     */
    public function getAttr(Collection $collection, $transformer = ProductAttributeTransformer::class)
    {
        return fractal(
            $collection,
            new $transformer($this)
        )->toArray()['data'] ?? [];
    }


    public function getAttrCollection(Collection $collection, $transformer = ProductAttributeTransformer::class)
    {
        $result = $collection
            ->groupBy('name')
            ->map(function ($items, $key) {
                return [
                    'name' => $key,
                    'slug' => $items->first()['slug'],
                    'stock' => $items->sum('pivot.stock'),
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
     * has price
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne<Price, Product>
     */
    public function price()
    {
        return $this->morphOne(Price::class, 'priceable');
    }
}
