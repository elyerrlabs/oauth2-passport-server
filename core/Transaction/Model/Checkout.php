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

use Core\Ecommerce\Transformer\Admin\CheckoutTransformer;
use Core\Transaction\Model\User;
use App\Models\Common\Order;
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
        'user_id'
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

    /**
     * user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, Checkout>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
