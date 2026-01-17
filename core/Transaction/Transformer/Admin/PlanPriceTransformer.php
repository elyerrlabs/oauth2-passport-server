<?php

namespace Core\Transaction\Transformer\Admin;

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

use App\Models\Common\Price;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class PlanPriceTransformer extends TransformerAbstract
{
    use Asset;

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Price $price)
    {
        return [
            'id' => $price->id,
            'billing_period' => $price->billing_period,
            'billing_period_name' => config("billing.period.{$price->billing_period}.id"),
            'currency' => $price->currency,
            'amount' => $price->amount,
            'amount_format' => $this->formatMoney($price->amount),
            'expiration' => $this->format_date(billing_get_expiration_date($price->billing_period), 'Y-m-d H:i'),
            'created' => $this->format_date($price->created_at),
            'updated' => $this->format_date($price->updated_at),
            'links' => [
                'destroy' => route('transaction.admin.plans.prices.destroy', [
                    'plan' => $price->priceable->id,
                    'price' => $price->id
                ])
            ]
        ];
    }
}
