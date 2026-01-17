<?php

namespace Core\Transaction\Transformer\User;

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


use Core\Transaction\Model\Package;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;
use Core\Transaction\Transformer\User\UserTransactionTransformer;

class UserPackageTransformer extends TransformerAbstract
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
    public function transform(Package $package)
    {
        return [
            'id' => $package->id,
            'start_at' => $this->format_date($package->start_at),
            'end_at' => $this->format_date($package->end_at),
            'last_renewal_at' => $this->format_date($package->lastTransaction->created_at),
            'is_recurring' => $package->is_recurring,
            'billing_period' => billing_get_status_name($package->lastTransaction->billing_period),
            'status' => $package->lastTransaction->status,
            'meta' => $package->meta,
            'transaction' => $package->transform($package->lastTransaction, UserTransactionTransformer::class),
            'transactions' => $package->transform($package->transactions, UserTransactionTransformer::class),
            'created' => $this->format_date($package->created_at),
            'updated' => $this->format_date($package->updated),
            'links' => [
                'index' => route('transaction.subscriptions.index'),
                'show' => route('transaction.subscriptions.show', [
                    'transaction_code' => $package->transaction_code
                ]),
                'recurring' => route('transaction.recurring.payment', [
                    'package_id' => $package->id
                ])
            ]
        ];
    }

    /**
     * Return the original attribute 
     * @param mixed $index
     * @return string|null
     */
    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'price' => 'price',
            'currency' => 'currency',
            'start_at' => 'start_at',
            'end_at' => 'end_at',
            'meta' => 'meta',
            'is_recurring' => 'is_recurring',
            'status' => 'status',
            'create' => 'created_at',
            'updated' => 'updated_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
