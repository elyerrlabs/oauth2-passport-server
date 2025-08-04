<?php

namespace App\Transformers\Subscription;

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
 */

use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;
use App\Models\Subscription\Transaction;

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
            'currency' => $transaction->currency,
            'status' => $transaction->status, 
            'subtotal' => $this->formatMoney($transaction->subtotal),
            'total' => $this->formatMoney($transaction->total),
            'payment_method' => $transaction->payment_method,
            'billing_period' => $transaction->billing_period,
            'renew' => $transaction->renew,
            'session_id' => $transaction->session_id,
            'payment_intent_id' => $transaction->payment_intent_id,
            'payment_url' => $transaction->package->isCancelled() ? null : $transaction->payment_url,
            'response' => $transaction->response,
            'meta' => $transaction->meta,
            'code' => $transaction->code,
            'activated' => $transaction->user_id ? $transaction->user->email : 'System',
            'created' => $this->format_date($transaction->created_at),
            'updated' => $this->format_date($transaction->updated_at),
            'payment_method_id' => $transaction->payment_method_id,
            'links' => [
                'index' => route('admin.transactions.index'),
                'activate' => route('admin.transactions.activate', ['transaction' => $transaction->id]),
                'cancel' => route('users.subscriptions.cancel', ['transaction_id' => $transaction->id])
            ]
        ];
    }


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
}
