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
            'id' => 'one_time',
            'name' => 'One time',
        ],
        'daily' => [
            'interval' => 1,
            'unit' => 'days',
            'id' => 'daily',
            'name' => 'Daily',
        ],
        'weekly' => [
            'interval' => 1,
            'unit' => 'weeks',
            'id' => 'weekly',
            'name' => 'Weekly',
        ],
        'biweekly' => [
            'interval' => 2,
            'unit' => 'weeks',
            'id' => 'biweekly',
            'name' => 'Biweekly',
        ],
        'monthly' => [
            'interval' => 1,
            'unit' => 'months',
            'id' => 'monthly',
            'name' => 'Monthly',
        ],
        'quarterly' => [
            'interval' => 3,
            'unit' => 'months',
            'id' => 'quarterly',
            'name' => 'Quarterly',
        ],
        'semiannual' => [
            'interval' => 6,
            'unit' => 'months',
            'id' => 'semiannual',
            'name' => 'Semiannual',
        ],
        'annual' => [
            'interval' => 1,
            'unit' => 'years',
            'id' => 'annual',
            'name' => 'Annual',
        ],
        'biannual' => [
            'interval' => 2,
            'unit' => 'years',
            'id' => 'biannual',
            'name' => 'Biannual',
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
            'id' => 'pending',
            'name' => 'Pending',
            'message' => 'The payment has been initiated but not yet confirmed.',
        ],
        'processing' => [
            'id' => 'processing',
            'name' => 'Processing',
            'message' => 'The payment is currently being processed.',
        ],
        'successful' => [
            'id' => 'successful',
            'name' => 'Successful',
            'message' => 'The payment was completed successfully.',
        ],
        'failed' => [
            'id' => 'failed',
            'name' => 'Failed',
            'message' => 'The payment failed due to an error (insufficient funds, network issue, etc.).',
        ],
        'cancelled' => [
            'id' => 'cancelled',
            'name' => 'Cancelled',
            'message' => 'The payment was canceled before processing.',
        ],
        'refunded' => [
            'id' => 'refunded',
            'name' => 'Refunded',
            'message' => 'The payment was fully refunded to the user.',
        ],
        'partially_refunded' => [
            'id' => 'partially_refunded',
            'name' => 'Partially refunded',
            'message' => 'Only a portion of the payment was refunded.',
        ],
        'disputed' => [
            'id' => 'disputed',
            'name' => 'Disputed',
            'message' => 'The payment is under dispute (e.g., suspected fraud).',
        ],
        'chargeback' => [
            'id' => 'chargeback',
            'name' => 'Chargeback',
            'message' => 'The payment was reversed by the bank or payment processor.',
        ],
        'expired' => [
            'id' => 'expired',
            'name' => 'Expired',
            'message' => 'The payment was not completed before the expiration deadline.',
        ],
        'on_hold' => [
            'id' => 'on_hold',
            'name' => 'On hold',
            'message' => 'The payment is temporarily on hold for manual review or fraud prevention.',
        ],
    ],

];
