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
 *
 * SPDX-License-Identifier: LicenseRef-NC-Open-Source-Project
 */

return [

    "merge" => [

        "user_routes" => [

            "partner" => [
                "id" => "partner",
                "name" => __("Partner"),
                "route" => "partner.dashboard",
                "icon" => "mdi-account-cash",
                'service' => 'reseller:partner',
            ],
        ],

        "admin_dashboard" => [
            
            "partner" => [
                "id" => "list",
                "name" => __("Partners"),
                "route" => "partner.admin.partner.index",
                "icon" => "mdi-handshake-outline",
                'service' => 'administrator:partner',
            ]
        ],
    ],

    "partner_routes" => [
        [
            "id" => "dashboard",
            "name" => __("Dashboard"),
            "route" => "partner.dashboard",
            "icon" => "mdi-account-cash",
            'service' => 'reseller:partner',
        ],
        [
            "id" => "referral_link",
            "name" => __("Referral Link"),
            "route" => "partner.generate",
            "icon" => "mdi-reload",
            'service' => 'reseller:partner',
        ],
        [
            "id" => "sales",
            "name" => __("Sales"),
            "route" => "partner.sales",
            "icon" => "mdi-cash-multiple",
            'service' => 'reseller:partner',
        ],
        [
            "id" => "list",
            "name" => __("Partners"),
            "route" => "partner.admin.partner.index",
            "icon" => "mdi-handshake-outline",
            'service' => 'administrator:partner',
        ]
    ]
];