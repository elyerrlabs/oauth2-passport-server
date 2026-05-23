<?php

namespace App\Http\Middleware;

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

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackReferralCode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $referral = $request->query('referral_code');

        // Save referral code into the session
        if ($referral) {
            $isValidFormat = preg_match(
                '/^[A-Za-z0-9]{1,5}_[A-Za-z0-9]{1,100}$/',
                $referral
            );

            if ($isValidFormat) {
                session()->put('referral_code', $referral);
            }
        }

        // Check if it the referral code exist and add to the url 
        if ($request->routeIs('transaction.plans.index') && !$request->has('referral_code') && session()->has('referral_code')) {
            return redirect()->to(route(
                'transaction.plans.index',
                [
                    'referral_code' => session('referral_code')
                ]
            ));
        }

        return $next($request);
    }
}
