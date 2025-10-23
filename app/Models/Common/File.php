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
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class File extends Master
{

    use Dynamic;

    public $tag = 'common_files';

    protected $table = "files";

    protected $fillable = [
        'name',
        'original_name',
        'mime_type',
        'extension',
        'disk',
        'path',
        'size',
        'visibility',
    ];

    protected $append = [
        'url'
    ];

    /**
     * Polymorphic relationship
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo<\Illuminate\Database\Eloquent\Model, File>
     */
    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getUlrAttribute()
    {
        return Storage::url($this->path);
    }
}
