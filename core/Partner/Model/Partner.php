<?php

namespace Core\Partner\Model;

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

use App\Models\Master; 
use Core\Partner\Model\User;
use Core\Transaction\Model\Transaction;  
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Partner extends Master
{
    use HasFactory;

    public $fillable = [
        'code',
        'commission_rate',
        'user_id'
    ];

    /**
     * Belongs to user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * transactions
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Referral Link
     * @return string|null
     */
    public function referLinks()
    {
        return $this->code ? route('transaction.plans.index', ['referral_code' => $this->code]) : null;
    }

    /**
     * Retrieve the commission rate
     * @return float|int
     */
    public function getCommissionRate()
    {
        return $this->commission_rate / 100;
    }
}
