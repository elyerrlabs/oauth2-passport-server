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
