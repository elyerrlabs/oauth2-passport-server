<?php
namespace App\Models\Subscription;

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
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Price extends Master
{
    use HasFactory;

    public $fillable = [
        'amount',
        'billing_period',
        'currency'
    ];

    /**
     *  priceable
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function priceable()
    {
        return $this->morphTo();
    }

    public function setAmountAttribute($value)
    {
        $price = str_replace('.', '', $value);
        $this->attributes['amount'] = $price;
    }
}
