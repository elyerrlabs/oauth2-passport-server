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

use App\Models\Master as master;
use Core\User\Model\Scope;

class Role extends master
{

    /**
     * Table name
     * @var string
     */
    public $table = "roles";

    /**
     * Fillable attributes
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'system',

    ];

    /**
     * Casts properties
     * @var array
     */
    public $casts = [
        "system" => "boolean",
    ];

    /**
     * Slug
     * @param mixed $value
     * @return void
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = normalizeSlug($value);
    }

    /**
     * default roles
     * @return mixed
     */
    public static function rolesByDefault()
    {
        return json_decode(file_get_contents(base_path('database/extra/roles.json')));
    }

    /**
     * Relationship with scopes 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scopes()
    {
        return $this->hasMany(Scope::class);
    }
}
