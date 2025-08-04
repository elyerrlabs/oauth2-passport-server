<?php

namespace App\Models\User;

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
use App\Models\Subscription\Scope;
use App\Transformers\User\UserScopeTransformer;

class UserScope extends Master
{

    /**
     * Table name
     * @var string
     */
    protected $table = 'user_scope';

    /**
     * Transformer of class
     * @var 
     */
    public $transformer = UserScopeTransformer::class;

    /**
     * Fillable attributes
     * @var array
     */
    public $fillable = [
        'scope_id',
        'user_id',
        'package_id',
        'end_date',
    ];

    protected $appends = [
        'gsr_id',
    ];

    protected $casts = [
        'end_date' => 'datetime',
    ];


    public function getGsrIdAttribute()
    {
        return $this->scope->getGsrID();
    }

    /**
     * Belongs to 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Belongs to
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scope()
    {
        return $this->belongsTo(Scope::class);
    }

    public function revoked()
    {
        if (empty($this->end_date)) {
            return 'unlimited';
        }

        return $this->end_date < now() ? 'revoked' : 'active';
    }
}
