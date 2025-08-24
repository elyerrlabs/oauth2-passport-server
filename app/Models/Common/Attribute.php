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
use Core\Ecommerce\Model\Product; 

class Attribute extends Master
{

    public $tag = 'common_attribute';
    
    /**
     * Table name
     * @var string
     */
    protected $table = "attributes";

    /**
     * Disable timestamps
     * @var 
     */
    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'value',
        'widget',
        'multiple',
        'unit_id'
    ];

    protected $casts = [
        'multiple' => 'boolean'
    ];

    /**
     * Set the name in lowercase
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    /**
     * Set the slug in lowercase
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = strtolower($value);
    }

    /**
     * Set the type in lowercase
     */
    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = strtolower($value);
    }

    /**
     * Set the value in lowercase
     */
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = strtolower($value);
    }

    /**
     * Set the widget in lowercase
     */
    public function setWidgetAttribute($value)
    {
        $this->attributes['widget'] = strtolower($value);
    }

    /**
     * Set multiple as boolean
     */
    public function setMultipleAttribute($value)
    {
        $this->attributes['multiple'] = filter_var($value, FILTER_VALIDATE_BOOL);
    }

    /**
     * Has unit
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<Unit, Attribute>
     */
    public function unit()
    {
        return $this->hasOne(Unit::class);
    }

    /**
     * Products
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany<Product, Attribute>
     */
    public function products()
    {
        return $this->morphedByMany(Product::class, 'attributable');
    }
}
