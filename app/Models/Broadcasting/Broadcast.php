<?php

namespace App\Models\Broadcasting;

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
use App\Transformers\Broadcast\BroadcastTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\QueryException;

class Broadcast extends Master
{
    use HasFactory;

    /**
     * Table name
     * @var string
     */
    public $table = "broadcasts";

    /**
     * Transformer class
     * @var 
     */
    public $transformer = BroadcastTransformer::class;

    /**
     * Fillable properties
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'slug',
        'system'
    ];

    /**
     * Cast properties
     * @var array
     */
    public $casts = [
        'system' => 'boolean',
    ];

    /**
     * Retrieve default channels for the system
     * @return mixed
     */
    public static function channelsByDefault()
    {
        return json_decode(file_get_contents(base_path('database/extra/channels.json')));
    }
}
