<?php

namespace App\Models\Common;

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
use App\Repositories\Contracts\Dynamic; 

class Tag extends Master
{
    use Dynamic;

    public $tag = "common_tag";

    /**
     * Table name
     * @var string
     */
    protected $table = "tags";

    public $timestamps = false;

    /**
     * Fillable attribute
     * @var array
     */
    protected $fillable = [
        'name',
        'slug'
    ];
}
