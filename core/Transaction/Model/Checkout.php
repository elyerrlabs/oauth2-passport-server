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

use App\Models\Common\Order;
use Core\Transaction\Transformer\Admin\CheckoutTransformer;
use App\Models\Master;

class Checkout extends Master
{

    public $tag = "checkout";

    /**
     * Table name
     * @var string
     */
    protected $table = "checkouts";

    /**
     * Transformers
     * @var 
     */
    public $transformer = CheckoutTransformer::class;

    /**
     * Fillable
     * @var array
     */
    protected $fillable = [
        'transaction_code',
        'code',
        'delivery_address',
    ];

    public $casts = [
        'delivery_address' => 'json'
    ];

    /**
     * The last transaction
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lastTransaction()
    {
        return $this->hasOne(Transaction::class, 'code', 'transaction_code');
    }

    /**
     * Transactions
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    /**
     * Has orders
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Order, Checkout>
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
