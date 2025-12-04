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

use App\Transformers\File\FilePrivateTransformer;
use Core\Transaction\Model\Refund;
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
                'id' => $refund->user->id,
                'name' => $refund->user->name,
                'last_name' => $refund->user->last_name,
                'email' => $refund->user->email,
            ],
            'assigned_by' => [
                'id' => $refund->assignedBy->id ?? '',
                'name' => $refund->assignedBy->name ?? '',
                'last_name' => $refund->assignedBy->last_name ?? '',
                'email' => $refund->assignedBy->email ?? '',
            ],
            'assigned_to' => [
                'id' => $refund->assignedTo->id ?? '',
                'name' => $refund->assignedTo->name ?? '',
                'last_name' => $refund->assignedTo->last_name ?? '',
                'email' => $refund->assignedTo->email ?? '',
            ],
            'parent_transaction' => fractal($refund->parentTransaction, TransactionTransformer::class)->toArray()['data'] ?? [],
            'transaction' => fractal($refund->transaction, TransactionTransformer::class)->toArray()['data'] ?? [],
            'appeal' => fractal($refund->children, static::class)->toArray()['data'] ?? [],
            'files' => fractal($refund->files, new FilePrivateTransformer($refund->id))->toArray()['data'] ?? [],
            'web' => [
                'index' => route('transaction.admin.refunds.index'),
                'show' => route('transaction.admin.refunds.show', ['refund' => $refund->id]),
                'update' => route('transaction.admin.refunds.update', ['refund' => $refund->id]),
                'assignTo' => route('transaction.admin.refunds.assignto', ['id' => $refund->id]),
            ]
        ];
    }
}
