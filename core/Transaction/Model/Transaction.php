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

use App\Models\Master;
use App\Repositories\Contracts\Dynamic;
use Core\User\Model\User;
use Core\Partner\Model\Partner;
use Core\Transaction\Model\Refund;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Master
{
    use HasFactory, Dynamic;

    public $table = "transactions";

    /**
     * tag
     * @var string
     */
    public $tag = 'transactions';

    protected $fillable = [
        'currency',
        'type',
        'status',
        'total',
        'payment_method',
        'billing_period',
        'renew',
        'cancellation_at',
        'session_id',
        'payment_intent_id',
        'payment_url',
        'response', //save response
        'meta', //save package
        'code', // unique code
        'partner_id', // if of the partner
        'user_id',
        'activated_by',
        'partner_commission_rate',
        'payment_method_id',
    ];

    protected $casts = [
        'response' => 'array',
        'renew' => 'boolean',
        'meta' => 'array'
    ];

    /**
     * Currency setter
     * @param mixed $value
     * @return void
     */
    public function setCurrencyAttribute($value)
    {
        $this->attributes['currency'] = strtolower($value);
    }

    /**
     * Get the user who activated or execute the transaction
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activatedBy()
    {
        return $this->belongsTo(User::class, 'activated_by');
    }

    /**
     * Get the owner of the transaction
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, Transaction>
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Summary of morph
     */
    public function transactionable()
    {
        return $this->morphTo();
    }

    /**
     * Partner
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Has refund
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function refund()
    {
        return $this->hasOne(Refund::class, 'transaction_id');
    }
}
