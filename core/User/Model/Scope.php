<?php
namespace Core\User\Model;

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
        'api_key'
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
        'api_key' => 'boolean'
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
}
