<?php

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

return [

    //redirect after login
    "redirect_to" => "/account",

    // Force the schema mode ( false to http and true for https)
    "schema_mode" => env('SCHEMA_HTTPS', 'https'),

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
];
