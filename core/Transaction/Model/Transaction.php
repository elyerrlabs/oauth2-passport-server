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
