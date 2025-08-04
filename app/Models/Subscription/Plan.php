<?php
namespace App\Models\Subscription;

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
use App\Models\Subscription\Price;
use App\Models\Subscription\Scope;
use App\Transformers\Subscription\PlanTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Transformers\Subscription\PlanPriceTransformer;
use App\Transformers\Subscription\PlanScopeTransformer;

class Plan extends Master
{
    use HasFactory;

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
