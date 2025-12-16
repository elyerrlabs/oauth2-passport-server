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

    "system" => [

        "clients" => [
            "oauth_developers" => [
                "status" => true,
                "description" => "Disabled developer options for clients, include oauth api and oauth clients"
            ],
            "api" => [
                "status" => true,
                "description" => "Disabled API options for users"
            ],
            "oauth" => [
                "status" => true,
                "description" => "Disabled oauth2 and openID clients creation for users"
            ]
        ],

        'guest' => [
            'register' => [
                "status" => true,
                "description" => "Disabled users registration to the system"
            ],
            'documentation' => [
                "status" => true,
                "description" => "Disabled documentation section to the system"
            ],
            'landing' => [
                "status" => true,
                "description" => "Disabled landing page to the system"
            ]
        ]
    ],

];