<?php

namespace Core\Transaction\Model;

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
use Core\User\Model\Scope;
use App\Models\Common\Price; 
use Core\Transaction\Transformer\Admin\PlanTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Core\Transaction\Transformer\Admin\PlanScopeTransformer;

class Plan extends Master
{
    use HasFactory;


    public $tag = "subscription_plan";

    protected $table = "plans";

    public $transformer = PlanTransformer::class;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'active',
        'bonus_activated',
        'bonus_duration',
        'trial_enabled',
        'trial_duration'
    ];

    protected $casts = [
        'public' => 'boolean',
        'active' => 'boolean',
        'bonus_activate' => 'boolean'
    ];

    /**
     * Show the scopes available for the plan
     */
    public function assignedScopes($scopes)
    {
        $scopes = $scopes->where('active', 1);
        return $this->transform($scopes, new PlanScopeTransformer($this));
    }

    /**
     * Relationship with scopes
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function scopes()
    {
        return $this->belongsToMany(Scope::class);
    }

    /**
     * Summary of prices
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function prices()
    {
        return $this->morphMany(Price::class, 'priceable');
    }
}
