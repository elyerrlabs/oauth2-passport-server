<?php

namespace Core\Transaction\Services\Payment\Contracts;

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
}
