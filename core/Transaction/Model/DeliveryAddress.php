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
use Core\Transaction\Model\User;

class DeliveryAddress extends Master
{
    protected $table = "delivery_addresses";

    /**
     * Summary of fillable
     * @var array
     */
    protected $fillable = [
        'full_name',
        'country',
        'state',
        'city',
        'district',
        'address',
        'address_line_2',
        'postal_code',
        'phone',
        'secondary_phone',
        'references',
        'user_id',
    ];

    /**
     * belongs to user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, DeliveryAddress>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
