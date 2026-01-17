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

use Core\Transaction\Model\DeliveryAddress;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class DeliveryAddressTransformer extends TransformerAbstract
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
    public function transform(DeliveryAddress $deliveryAddress)
    {
        return [
            'id' => $deliveryAddress->id,
            'full_name' => $deliveryAddress->full_name,
            'country' => $deliveryAddress->country,
            'state' => $deliveryAddress->state,
            'city' => $deliveryAddress->city,
            'district' => $deliveryAddress->district,
            'address' => $deliveryAddress->address,
            'address_line_2' => $deliveryAddress->address_line_2,
            'postal_code' => $deliveryAddress->postal_code,
            'phone' => $deliveryAddress->phone,
            'secondary_phone' => $deliveryAddress->secondary_phone,
            'references' => $deliveryAddress->references,
            'links' => [
                'index' => route('transaction.delivery.addresses.index'),
                'store' => route('transaction.delivery.addresses.store'),
                'delete' => route('transaction.delivery.addresses.delete', ['id' => $deliveryAddress->id]),
            ]
        ];
    }
}
