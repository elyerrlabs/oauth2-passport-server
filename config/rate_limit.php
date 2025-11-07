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

    /**
     * Global settings
     */
    'general' => [
        'api' => [
            'limit' => 300,
            'block_time' => 120,
            'name' => 'Rate Limit for API'
        ],
        'gateway' => [
            'limit' => 300,
            'block_time' => 120,
            'name' => 'Rate Limit for Gateway'
        ],
        'token' => [
            'limit' => 300,
            'block_time' => 120,
            'name' => 'Rate Limit Oauth2 and OpenID Connect'
        ],
        'passport' => [
            'limit' => 30,
            'block_time' => 60,
            'name' => 'Rate Limit for passport routes'
        ],
        'broadcast' => [
            'limit' => 300,
            'block_time' => 120,
            'name' => "Rate Limit for Broadcasting"
        ],
        'settings' => [
            'limit' => 300,
            'block_time' => 120,
            'name' => "Rate Limit for Settings"
        ],
        'auth' => [
            'limit' => 300,
            'block_time' => 120,
            'name' => "Rate Limit for Auth routes"
        ],
        'public' => [
            'limit' => 300,
            'block_time' => 120,
            'name' => "Rate Limit for Public routes"
        ],
    ],
];
