<?php

namespace Core\Transaction\Services\Payment\Contracts;

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

use Core\Transaction\Model\Transaction;

interface PaymentMethod
{
    /**
     * Process a one-time payment
     *
     * @param array $data Payment data
     * @return mixed Result of payment process (e.g. session object, confirmation)
     */
    public function buy(array $data);

    /**
     * Charge recurring payment
     * @param array $package
     * @return void
     */
    public function chargeRecurringPayment(array $package);

    /**
     * Cancel a subscription or payment process
     *
     * @param Transaction $transaction Transaction model instance
     * @return mixed Result of cancellation process
     */
    public function cancel(Transaction $transaction);

    /**
     * Summary of forceActivation
     * @param array $response
     * @return void
     */
    public function forceActivation(array $response);

    /**
     * Summary of refund 
     * @param array $transaction
     * @return void
     */
    public function refund(array $transaction);
}
