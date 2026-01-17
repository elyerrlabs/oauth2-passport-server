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

use Elyerr\ApiResponse\Assets\Asset;
use Core\Transaction\Model\Transaction;
use League\Fractal\TransformerAbstract;

class UserTransactionTransformer extends TransformerAbstract
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
            'code' => $transaction->code,
            'currency' => strtoupper($transaction->currency),
            'status' => $transaction->status,
            'total' => $this->formatMoney($transaction->total),
            'cents' => $transaction->total,
            'payment_method' => $transaction->payment_method,
            'billing_period' => $transaction->billing_period,
            'renew' => $transaction->renew ? true : false,
            'session_id' => $transaction->session_id,
            'payment_intent_id' => $transaction->payment_intent_id,
            'payment_url' => $transaction->payment_url ?? $transaction->response['url'],
            'meta' => $transaction->meta,
            'refund' => fractal($transaction->refund, UserRefundTransformer::class)->toArray()['data'] ?? [],
            'created' => $this->format_date($transaction->created_at),
            'updated' => $this->format_date($transaction->updated_at),
            'refund_expiration_date' => $this->getExpirationDate($transaction),
            'refund_expired' => $this->hasExpired($transaction),
            'links' => [
                'activate' => route('transaction.transactions.activate', ['transaction' => $transaction->id]),
                'cancel' => route('transaction.subscriptions.cancel', ['transaction_id' => $transaction->id])
            ]
        ];
    }


    /**
     * Expiration date
     * @param mixed $transaction
     * @return string
     */
    public function getExpirationDate($transaction)
    {
        return $this->format_date(
            $transaction->created_at->addDays(
                config('billing.refund.grace_period_days', 60)
            )
        );
    }

    /**
     * Verify is it the expiration of transaction
     * @param mixed $transaction
     * @return bool
     */
    public function hasExpired($transaction)
    {

        $last_day = $transaction->created_at->addDays(config('billing.refund.grace_period_days', 60));
        return now() > $last_day ? true : false;
    }
}
