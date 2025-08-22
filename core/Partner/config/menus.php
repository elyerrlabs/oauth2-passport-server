<?php

return [
    "partner_dashboard" => [
        "name" => "Partner",
        "route" => "partner.dashboard",
        "icon" => "mdi-account-cash",
        'show' => 'reseller',
    ],

    "partner_routes" => [
        [
            "id" => "dashboard",
            "name" => "Dashboard",
            "route" => "partner.dashboard",
            "icon" => "mdi-account-cash",
            'service' => 'reseller',
        ],
        [
            "id" => "referral_link",
            "name" => "Referral Link",
            "route" => "partner.generate",
            "icon" => "mdi-reload",
            'service' => 'reseller',
        ],
        [
            "id" => "sales",
            "name" => "Sales",
            "route" => "partner.sales",
            "icon" => "mdi-cash-multiple",
            'service' => 'reseller',
        ],
        [
            "id" => "list",
            "name" => "Partners",
            "route" => "partner.admin.partner.index",
            "icon" => "mdi-handshake-outline",
            'service' => 'reseller',
        ]
    ]
];