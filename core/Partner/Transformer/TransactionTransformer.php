<?php
namespace Core\Partner\Transformer;

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

use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;
use Core\Transaction\Model\Transaction;

class TransactionTransformer extends TransformerAbstract
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
    public function transform(Transaction $transaction)
    {
        return [
            'id' => $transaction->id,
            'currency' => strtoupper($transaction->currency),
            'status' => $transaction->status,
            'cents' => (int) $transaction->total,
            'partner_commission_rate' => floatval($transaction->partner_commission_rate),
            'total' => $this->formatMoney($transaction->total),
            'commission' => $this->formatMoney(($transaction->total * ($transaction->partner_commission_rate / 100))),
            'commission_cents' => ($transaction->total * ($transaction->partner_commission_rate / 100)),
            'created' => $this->format_date($transaction->created_at),
            'updated' => $this->format_date($transaction->updated_at),
        ];
    }


    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'currency' => 'currency',
            'status' => 'status',
            'subtotal' => 'subtotal',
            'total' => 'total',
            'partner_commission_rate' => 'partner_commission_rate',
            'renew' => 'renew',
            'created' => 'created_at',
            'updated' => 'updated_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
