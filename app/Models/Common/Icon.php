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
use Illuminate\Database\Eloquent\Model;

class Icon extends Master
{

    use Dynamic;
    
    public $tag = 'common_icon';

    /**
     * Table name
     * @var string
     */
    protected $table = "icons";

    /**
     * Fillable attribute
     * @var array
     */
    protected $fillable = [
        'class',
        'origin'
    ];

    /**
     * Polymorphic relations
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo<Model, Icon>
     */
    public function iconable()
    {
        return $this->morphTo();
    }
}
