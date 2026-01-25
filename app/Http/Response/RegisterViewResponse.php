<?php

namespace App\Http\Response;

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

final class RegisterViewResponse implements \Laravel\Fortify\Contracts\RegisterViewResponse
{
    public function toResponse($request)
    {
        // If the request has a redirect_to parameter, store it in the session
        if (!empty($request->input('redirect_to'))) {
            session()->put('url.intended', $request->input('redirect_to'));
        }

        if ($request->user()) {
            return redirect()->route('users.dashboard');
        }

        return response()->view('auth.register');
    }
}
