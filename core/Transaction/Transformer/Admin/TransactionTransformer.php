<?php

namespace Core\Transaction\Transformer\Admin;

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
            'activated' => $transaction->user_id ? $transaction->user->email : 'System',
            'created' => $this->format_date($transaction->created_at),
            'updated' => $this->format_date($transaction->updated_at),
            'payment_method_id' => $transaction->payment_method_id,
            'partner_commission_rate' => $transaction->partner_commission_rate,
            'owner' => $this->owner($transaction->owner),
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
