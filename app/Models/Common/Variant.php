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
use App\Models\Common\Order;
use App\Repositories\Contracts\Dynamic;

class Variant extends Master
{
    use Dynamic;
    
    public $tag = 'common_variant';

    /**
     * Table name
     * @var string
     */
    protected $table = "variants";


    protected $fillable = [
        'name',
        'description',
        'stock',
    ];

    /**
     * set stock
     * @param mixed $value
     * @return void
     */
    public function setStock($value)
    {
        $value = str_replace([',', '.'], '', $value);
        $this->attributes['stock'] = filter_var($value, FILTER_VALIDATE_INT);
    }

    /**
     * Variants
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function variantable()
    {
        return $this->morphTo();
    }

    /**
     * Orders
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function orders()
    {
        return $this->morphMany(Order::class, 'orderable');
    }

    /**
     * Summary of price
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function price()
    {
        return $this->morphOne(Price::class, 'priceable');
    }
}
