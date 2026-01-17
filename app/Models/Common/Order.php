<?php

namespace App\Models\Common;

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
        return $this->belongsTo(Checkout::class);
    }
}
