<?php

namespace App\Providers;

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

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class MorphServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $morph = [
            (new \Core\User\Model\User())->tag => \Core\User\Model\User::class,
            (new \App\Models\Common\File())->tag => \App\Models\Common\File::class,
            (new \App\Models\Common\Attribute)->tag => \App\Models\Common\Attribute::class,
            (new \App\Models\Common\Icon())->tag => \App\Models\Common\Icon::class,
            (new \App\Models\Common\Tag())->tag => \App\Models\Common\Tag::class,
            (new \App\Models\Common\Order())->tag => \App\Models\Common\Order::class,
            (new \App\Models\Common\Variant())->tag => \App\Models\Common\Variant::class
        ];

        Relation::morphMap(array_merge(
            config('morph'),
            $morph
        ));

    }
}
