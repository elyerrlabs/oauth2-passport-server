<?php

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

return [

    //redirect after login
    "redirect_to" => "/account",

    // Default redirect after login or main page
    "home_page" => "/",

    // Custom cookie name for the session (null = default)
    "cookie_name" => null,

    // Token services for Laravel Passport (null = disabled)
    "passport_token_services" => null,

    // Time in minutes to allow account verification
    "verify_account_time" => 5,

    // Prevent creating users via Artisan commands
    "disable_create_user_by_command" => false,

    // Automatically delete users after X days (e.g., inactive or not verified)
    "destroy_user_after" => 30,

    // Expiration time (in minutes) for the 2FA code sent via email
    "code_2fa_email_expires" => 5,

    // Allow or block public registration
    "enable_register_member" => true,

    // Enable Content Security Policy (CSP) headers
    "csp_enabled" => false,

    //Policies
    "terms_url" => null,
    "privacy_url" => null,
    "policy_cookies" => null,

    "birthday" => [
        "active" => false, // Activate birthday
        "limit" => 18, // Limit of year to allow register users
    ],

    "demo" => [
        'enabled' => false,
        'email' => null,
        'password' => null
    ],
];
