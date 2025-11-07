<?php

namespace Core\Transaction\Model;

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

use Core\Transaction\Model\DeliveryAddress;
use Core\Transaction\Model\PaymentProvider;

class User extends \Core\User\Model\User
{
    public function paymentProviders()
    {
        return $this->hasMany(PaymentProvider::class, 'user_id');
    }

    /**
     * Has delivery addresses
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<DeliveryAddress, User>
     */
    public function DeliveryAddresses()
    {
        return $this->hasMany(DeliveryAddress::class);
    }

    /**
     * Has many checkout
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Checkout, User>
     */
    public function checkouts()
    {
        return $this->hasMany(Checkout::class);
    }

    /**
     * Get all transactions activated or executed by the user
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Transaction, User>
     */
    public function activatedByTransactions()
    {
        return $this->hasMany(Transaction::class, 'activated_by');
    }

    /**
     * Get all transactions owned by the users 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Transaction, User>
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }
}
