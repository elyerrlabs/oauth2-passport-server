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
        'expired' => [
            'id' => 'expired',
            'name' => 'Expired',
            'message' => 'The payment was not completed before the expiration deadline.',
        ],
    ],

    'types' => [
        'payment' => [
            'id' => 'payment',
            'name' => 'Payment',
            'description' => 'A normal user payment.',
        ],
        'refund' => [
            'id' => 'refund',
            'name' => 'Refund',
            'description' => 'A transaction that returns funds to the user.',
        ],
        'chargeback' => [
            'id' => 'chargeback',
            'name' => 'Chargeback',
            'description' => 'Funds were reversed by the bank or payment processor.',
        ],
        'dispute' => [
            'id' => 'dispute',
            'name' => 'Dispute',
            'description' => 'The transaction is being reviewed due to a claim or suspected issue.',
        ],
    ],

];
