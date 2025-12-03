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

use App\Models\Common\Refund;
use App\Transformers\File\FilePrivateTransformer;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class RefundTransformer extends TransformerAbstract
{

    use Asset;

    public function transform(Refund $refund)
    {
        return [
            'id' => $refund->id,
            'reason' => $refund->reason,
            'description' => $refund->description,
            'cents' => $refund->amount,
            'amount' => $this->formatMoney($refund->amount),
            'currency' => strtoupper($refund->currency),
            'type' => $refund->type, // 'refund','appeal'
            'status' => $refund->status, //'pending', 'under_review', 'approved', 'waiting_for_return','processing','completed','rejected','canceled'
            'user' => [
                'name' => $refund->user->name,
                'last_name' => $refund->user->last_name,
                'email' => $refund->user->email,
            ],
            'handled' => [
                'name' => $refund->handledBy->name ?? '',
                'last_name' => $refund->handledBy->last_name ?? '',
                'email' => $refund->handledBy->email ?? '',
            ],
            'transaction' => fractal($refund->refundable, TransactionTransformer::class)->toArray()['data'] ?? [],
            'appeal' => fractal($refund->children, static::class)->toArray()['data'] ?? [],
            'files' => fractal($refund->files, new FilePrivateTransformer($refund->id))->toArray()['data'] ?? [],
            'web' => [
                'index' => route('transaction.admin.refunds.index'),
                'show' => route('transaction.admin.refunds.show', ['refund' => $refund->id]),
                'update' => route('transaction.admin.refunds.update', ['refund' => $refund->id]),
            ]
        ];
    }
}
