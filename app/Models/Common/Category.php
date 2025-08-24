<?php

namespace App\Models\Common;

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
use App\Models\Common\Icon;
use Core\Ecommerce\Model\Product;
use Illuminate\Support\Collection;
use App\Transformers\File\FileTransformer;
use App\Transformers\Common\IconTransformer;

class Category extends Master
{
    
    public $tag = 'common_category';

    /**
     * Table name
     * @var string
     */
    protected $table = "categories";

    /**
     * Fillable attributes
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'tag',
        'description',
        'featured',
        'published'
    ];


    public $cats = [
        'featured' => 'boolean',
        'published' => 'boolean'
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * Summary of files
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<File, Category>
     */
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    /**
     * Has products
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Product, Category>
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /**
     * Has icon
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne<Icon, Category>
     */
    public function icon()
    {
        return $this->morphOne(Icon::class, 'iconable');
    }

    /**
     * Retrieve the all images
     * @param \Illuminate\Database\Eloquent\Collection $collection
     */
    public function getImages(Collection $collection, $transformer = FileTransformer::class)
    {
        return fractal($collection, $transformer)->toArray()['data'] ?? [];
    }

    /**
     * Retrieve the Icon
     * @param mixed $icon
     */
    public function getIcon($icon, $transformer)
    {
        return fractal($icon, $transformer)->toArray()['data'] ?? [];
    }


    // protected static function boot()
    // {
    //     parent::boot();
    //     static::creating(function ($category) {
    //         if (!$category->tag && isset($category->model_source)) {
    //             $category->tag = strtolower(class_basename($category->model_source));
    //         }
    //     });
    // }
}
