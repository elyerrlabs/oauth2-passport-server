<?php

namespace App\Models\Setting;

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
use App\Models\User\User;
use App\Transformers\Setting\TerminalTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Terminal extends Master
{
    use HasFactory;

    public $transformer = TerminalTransformer::class;

    protected $fillable = [
        'command',
        'output',
        'status',
        'user_id'
    ];

    public function whiteList()
    {
        return ['ls', 'git', 'whoami', 'uptime', 'php artisan', 'composer', 'npm'];
    }


    /**
     * Users relations
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
