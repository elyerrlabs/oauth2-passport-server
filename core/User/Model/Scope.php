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

use App\Models\Master;
use Core\User\Model\Role;
use Core\User\Model\UserScope;
use Core\Transaction\Model\Plan;
use Core\User\Transformer\Admin\ScopeTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Scope extends Master
{
    use HasFactory;

    /**
     * Name of table
     * @var string
     */
    protected $table = "scopes";

    public $transformer = ScopeTransformer::class;

    protected $fillable = [
        'service_id',
        'role_id',
        'public',
        'active',
        'api_key',
        'web'
    ];

    protected $appends = [
        'gsr_id'
    ];

    /**
     * Casts properties
     * @var array
     */
    protected $casts = [
        'public' => 'boolean',
        'active' => 'boolean',
        'api_key' => 'boolean',
        'web' => 'boolean'
    ];

    /**
     * get scope name
     * @return string
     */
    public function getGsrIdAttribute()
    {
        return $this->getGsrID();
    }

    /**
     * Relationship with service
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Summary of plan
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function plan()
    {
        return $this->belongsToMany(Plan::class);
    }

    /**
     * Relationship with scope
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relationship with the scopes
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scopeUsers()
    {
        return $this->hasMany(UserScope::class);
    }

    /**
     * Generate the scope id
     * @return string
     */
    public function getGsrID()
    {
        $group = $this->service->group->slug;
        $service = $this->service->slug;
        $role = $this->role->slug;
        return "{$group}:{$service}:{$role}";
    }

    /**
     * Getter
     */
    public function isApi()
    {
        return $this->api_key;
    }

    /**
     * Getter
     */
    public function isWeb()
    {
        return $this->web;
    }
}
