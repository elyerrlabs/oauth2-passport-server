<?php

namespace App\Providers;

/**
 * OAuth2 Passport Server — a centralized, modular authorization server
 * implementing OAuth 2.0 and OpenID Connect specifications.
 *
 * Copyright (c) 2026 Elvis Yerel Roman Concha
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * Author: Elvis Yerel Roman Concha
 * Contact: yerel9212@yahoo.es
 *
 * SPDX-License-Identifier: AGPL-3.0-or-later
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
            (new \App\Models\Common\Variant())->tag => \App\Models\Common\Variant::class,
        ];

        Relation::morphMap(array_merge(
            config('morph'),
            $morph
        ));

    }
}
