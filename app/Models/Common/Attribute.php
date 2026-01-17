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

class Attribute extends Master
{
    use Dynamic;

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
}
