<?php

namespace App\Http\Controllers\Web\Auth;

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

use App\Support\CacheKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Laravel\Fortify\Contracts\TwoFactorConfirmedResponse;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;

final class ConfirmedTwoFactorAuthenticationController extends \Laravel\Fortify\Http\Controllers\ConfirmedTwoFactorAuthenticationController
{

    /**
     * Enable two factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication  $confirm
     * @return \Laravel\Fortify\Contracts\TwoFactorConfirmedResponse
     */
    public function store(Request $request, ConfirmTwoFactorAuthentication $confirm)
    {
        $user = $request->user();

        $confirm($user, $request->input('code'));

        // Clean cache for this user
        Cache::forget(CacheKeys::user($user?->id));
        Cache::forget(CacheKeys::userAuth($user?->id));

        return app(TwoFactorConfirmedResponse::class);
    }
}
