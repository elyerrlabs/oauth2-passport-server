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
        'published',
        'parent_id'
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
     * Has children
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Category, Category>
     */
    public function children()
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    /**
     * Parent
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Category, Category>
     */
    public function parent()
    {
        return $this->belongsTo(static::class);
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
     * Has icon
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne<Icon, Category>
     */
    public function icon()
    {
        return $this->morphOne(Icon::class, 'iconable');
    }

}
