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
 */

use App\Models\Master;
use App\Models\User\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Transformers\Subscription\GroupTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Master
{
    use HasFactory, SoftDeletes;

    public $table = "groups";

    public $timestamps = false;

    public $transformer = GroupTransformer::class;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'system',
    ];

    public $casts = [
        'system' => 'boolean',
    ];

    /**
     * Retrieve default groups for the system
     * @return mixed
     */
    public static function groupByDefault()
    {
        return json_decode(file_get_contents(base_path('database/extra/groups.json')));
    }

    /**
     * Set relationship with services
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Users
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<User, Group>
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
