<?php

namespace App\Models\User;

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

use App\Models\Master;
use App\Models\User\User;
use Illuminate\Support\Str;
use App\Models\Subscription\Transaction;
use App\Transformers\Partner\PartnerTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Partner extends Master
{
    use HasFactory;

    /**
     * Transformer
     * @var 
     */
    public $transformer = PartnerTransformer::class;

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
        return $this->code ? route('plans.index', ['referral_code' => $this->code]) : null;
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
