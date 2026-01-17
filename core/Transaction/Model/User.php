<?php

namespace Core\Transaction\Model;

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

use Core\Transaction\Model\DeliveryAddress;
use Core\Transaction\Model\Refund;
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

    /**
     * Has refund
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function refunds()
    {
        return $this->hasMany(Refund::class, 'user_id');
    }

    /**
     * Refund Assign to
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function refundAssignTo()
    {
        return $this->hasMany(Refund::class, 'assigned_to');
    }


    /**
     * Refund Assign by
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function refundAssignBy()
    {
        return $this->hasMany(Refund::class, 'assigned_by');
    }
}
