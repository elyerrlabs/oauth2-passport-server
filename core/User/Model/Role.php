<?php
namespace Core\User\Model;

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

use App\Models\Master as master;
use Core\User\Model\Scope;

class Role extends master
{

    /**
     * Table name
     * @var string
     */
    public $table = "roles";

    /**
     * Fillable attributes
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'system',

    ];

    /**
     * Casts properties
     * @var array
     */
    public $casts = [
        "system" => "boolean",
    ];

    /**
     * Slug
     * @param mixed $value
     * @return void
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = normalizeSlug($value);
    }

    /**
     * default roles
     * @return mixed
     */
    public static function rolesByDefault()
    {
        return json_decode(file_get_contents(base_path('database/extra/roles.json')));
    }

    /**
     * Relationship with scopes 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scopes()
    {
        return $this->hasMany(Scope::class);
    }
}
