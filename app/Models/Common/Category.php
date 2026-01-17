<?php

namespace App\Models\Common;

/**
 * OAuth2 Passport Server — a centralized, modular authorization server
 * implementing OAuth 2.0 and OpenID Connect specifications.
 *
 * Copyright (c) 2026 Elvis Yerel Roman Concha
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * Author: Elvis Yerel Roman Concha
 * Contact: yerel9212@yahoo.es
 *
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


use App\Models\Master;
use App\Repositories\Contracts\Dynamic;
use App\Models\Common\Icon;

class Category extends Master
{
    use Dynamic;

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


    public function setParentIdAttribute($value)
    {
        try {
            $value = static::find($value)->id;
        } catch (\Throwable $th) {
            $value = null;
        }

        $this->attributes["parent_id"] = $value;
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
