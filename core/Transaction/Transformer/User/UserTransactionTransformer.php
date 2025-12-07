<?php

namespace Core\Transaction\Transformer\User;

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
