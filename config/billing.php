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

    'renew' => [
        'enable' => false,
        'hours_before' => 10,
        'bonus_enabled' => true,
        'grace_period_days' => 10,
    ],

    'taxes' => [
        'id' => null,
        'enabled' => false,
    ],

    'currency' => [
        'USD' => [
            'code' => 'USD',
            'name' => 'United States Dollar',
            'symbol' => '$',
        ],
        'EUR' => [
            'code' => 'EUR',
            'name' => 'Euro',
            'symbol' => '€',
        ],
        'JPY' => [
            'code' => 'JPY',
            'name' => 'Japanese Yen',
            'symbol' => '¥',
        ],
        'GBP' => [
            'code' => 'GBP',
            'name' => 'British Pound',
            'symbol' => '£',
        ],
        'AUD' => [
            'code' => 'AUD',
            'name' => 'Australian Dollar',
            'symbol' => 'A$',
        ],
        'CAD' => [
            'code' => 'CAD',
            'name' => 'Canadian Dollar',
            'symbol' => 'C$',
        ],
        'BRL' => [
            'code' => 'BRL',
            'name' => 'Brazilian Real',
            'symbol' => 'R$',
        ],
        'ARS' => [
            'code' => 'ARS',
            'name' => 'Argentine Peso',
            'symbol' => '$',
        ],
        'CLP' => [
            'code' => 'CLP',
            'name' => 'Chilean Peso',
            'symbol' => '$',
        ],
        'COP' => [
            'code' => 'COP',
            'name' => 'Colombian Peso',
            'symbol' => '$',
        ],
        'MXN' => [
            'code' => 'MXN',
            'name' => 'Mexican Peso',
            'symbol' => '$',
        ],
        'PEN' => [
            'code' => 'PEN',
            'name' => 'Peruvian Sol',
            'symbol' => 'S/',
        ],
        'UYU' => [
            'code' => 'UYU',
            'name' => 'Uruguayan Peso',
            'symbol' => '$U',
        ],
    ],

    'period' => [
        'one_time' => [
            'interval' => 0,
            'unit' => null,
            'name' => 'one_time',
            'id' => 'One time',
        ],
        'daily' => [
            'interval' => 1,
            'unit' => 'days',
            'name' => 'daily',
            'id' => 'Daily',
        ],
        'weekly' => [
            'interval' => 1,
            'unit' => 'weeks',
            'name' => 'weekly',
            'id' => 'Weekly',
        ],
        'biweekly' => [
            'interval' => 2,
            'unit' => 'weeks',
            'name' => 'biweekly',
            'id' => 'Biweekly',
        ],
        'monthly' => [
            'interval' => 1,
            'unit' => 'months',
            'name' => 'monthly',
            'id' => 'Monthly',
        ],
        'quarterly' => [
            'interval' => 3,
            'unit' => 'months',
            'name' => 'quarterly',
            'id' => 'Quarterly',
        ],
        'semiannual' => [
            'interval' => 6,
            'unit' => 'months',
            'name' => 'semiannual',
            'id' => 'Semiannual',
        ],
        'annual' => [
            'interval' => 1,
            'unit' => 'years',
            'name' => 'annual',
            'id' => 'Annual',
        ],
        'biannual' => [
            'interval' => 2,
            'unit' => 'years',
            'name' => 'biannual',
            'id' => 'Biannual',
        ],
    ],

    'methods' => [
        'stripe' => [
            'key' => 'stripe',
            'name' => 'Credit Card (Stripe)',
            'icon' => 'mdi-credit-card-outline',
            'enable' => true
        ],
        'paypal' => [
            'key' => 'paypal',
            'name' => 'PayPal',
            'icon' => 'mdi-contactless-payment',
            'enable' => false
        ],
        'offline' => [
            'key' => 'offline',
            'name' => 'Offline',
            'icon' => 'mdi-cash-register',
            'enable' => true
        ],
        'bank_transfer' => [
            'key' => 'bank_transfer',
            'name' => 'Bank Transfer',
            'icon' => 'mdi-bank-transfer',
            'enable' => false
        ],
        'wallet' => [
            'key' => 'wallet',
            'name' => 'Wallet',
            'icon' => 'mdi-wallet-outline',
            'enable' => false
        ],
        'crypto' => [
            'key' => 'crypto',
            'name' => 'Cryptocurrency',
            'icon' => 'mdi-currency-btc',
            'enable' => false
        ],
    ],


    'status' => [
        'pending' => [
            'name' => 'pending',
            'message' => 'The payment has been initiated but not yet confirmed.',
        ],
        'processing' => [
            'name' => 'processing',
            'message' => 'The payment is currently being processed.',
        ],
        'successful' => [
            'name' => 'successful',
            'message' => 'The payment was completed successfully.',
        ],
        'failed' => [
            'name' => 'failed',
            'message' => 'The payment failed due to an error (insufficient funds, network issue, etc.).',
        ],
        'cancelled' => [
            'name' => 'cancelled',
            'message' => 'The payment was canceled before processing.',
        ],
        'refunded' => [
            'name' => 'refunded',
            'message' => 'The payment was fully refunded to the user.',
        ],
        'partially_refunded' => [
            'name' => 'partially_refunded',
            'message' => 'Only a portion of the payment was refunded.',
        ],
        'disputed' => [
            'name' => 'disputed',
            'message' => 'The payment is under dispute (e.g., suspected fraud).',
        ],
        'chargeback' => [
            'name' => 'chargeback',
            'message' => 'The payment was reversed by the bank or payment processor.',
        ],
        'expired' => [
            'name' => 'expired',
            'message' => 'The payment was not completed before the expiration deadline.',
        ],
        'on_hold' => [
            'name' => 'on_hold',
            'message' => 'The payment is temporarily on hold for manual review or fraud prevention.',
        ],
    ],

];
