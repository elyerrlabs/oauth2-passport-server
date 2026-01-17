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
            'type' => $transaction->type,
            'status' => $transaction->status,
            'total' => $this->formatMoney($transaction->total),
            'payment_method' => $transaction->payment_method,
            'billing_period' => $transaction->billing_period,
            'renew' => $transaction->renew,
            'cancellation_at' => $transaction->cancellation_at,
            'session_id' => $transaction->session_id,
            'payment_intent_id' => $transaction->payment_intent_id,
            'payment_url' => $transaction->payment_url,
            'response' => $transaction->response,
            'meta' => $transaction->meta,
            'code' => $transaction->code,
            'activated' => $transaction->activated_by ? $transaction->activatedBy->email :  null,
            'created' => $this->format_date($transaction->created_at),
            'updated' => $this->format_date($transaction->updated_at),
            'payment_method_id' => $transaction->payment_method_id,
            'partner_commission_rate' => $transaction->partner_commission_rate,
            'owner' => $this->owner($transaction->user),
            'refund' => $this->refund($transaction->refund),
            'links' => [
                'index' => route('transaction.admin.transactions.index'),
                'activate' => route('transaction.transactions.activate', ['transaction' => $transaction->id]),
                'cancel' => route('transaction.subscriptions.cancel', ['transaction_id' => $transaction->id])
            ]
        ];
    }

    /**
     * Get original attributes
     * @param mixed $index
     * @return string|null
     */
    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'currency' => 'currency',
            'status' => 'status',
            'subtotal' => 'subtotal',
            'total' => 'total',
            'payment_method' => 'payment_method',
            'billing_period' => 'billing_period',
            'renew' => 'renew',
            'session_id' => 'session_id',
            'payment_intent_id' => 'payment_intent_id',
            'payment_url' => 'payment_url',
            'response' => 'response',
            'meta' => 'meta',
            'code' => 'code',
            'activated' => 'activated',
            'created' => 'created_at',
            'updated' => 'updated_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    /**
     * Retrieve the owner
     * @param mixed $owner
     * @return array|array{email: mixed, id: mixed, last_name: mixed, name: mixed}
     */
    private function owner($owner)
    {
        if (empty($owner)) {
            return [];
        }

        return [
            'id' => $owner->id,
            'name' => $owner->name,
            'last_name' => $owner->last_name,
            'email' => $owner->email,
        ];
    }


    /**
     * Return refund
     * @param mixed $refund
     * @return array|array{amount: mixed, currency: mixed, description: mixed, reason: mixed, status: mixed, type: mixed}
     */
    private function refund($refund)
    {
        if (empty($refund)) {
            return [];
        }

        return [
            'reason' => $refund->reason,
            'description' => $refund->description,
            'amount' => $this->formatMoney($refund->amount),
            'currency' => strtoupper($refund->currency),
            'type' => $refund->type,
            'status' => $refund->status,
        ];
    }
}
