<?php

namespace App\Models\Common;

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
use Core\Partner\Model\User;
use Core\Transaction\Model\Checkout;

class Order extends Master
{
    /**
     * Table
     * @var string
     */
    protected $table = "orders";

    /**
     * Fillable
     * @var array
     */
    protected $fillable = [
        'quantity',
        'meta',
        'user_id',
        'checkout_id'
    ];

    /**
     * Cast properties
     * @var array
     */
    protected $casts = [
        'quantity' => 'integer',
        'meta' => 'array'
    ];

    /**
     * orderable
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function orderable()
    {
        return $this->morphTo();
    }

    /**
     * Quantity 
     * @param mixed $value
     * @return void
     */
    public function setQuantityAttribute($value)
    {
        $this->attributes['quantity'] = filter_var($value, FILTER_VALIDATE_INT);
    }

    /**
     * User
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Belongs to th checkout
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Checkout, Order>
     */
    public function checkout()
    {
        return $this->belongsTo(Checkout::class, 'checkout_id');
    }
}
