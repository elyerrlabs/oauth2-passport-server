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

use App\Models\Common\Refund;
use League\Fractal\TransformerAbstract;

class UserRefundTransformer extends TransformerAbstract
{
    public function transform(Refund $refund)
    {

        return [
            'id' => $refund->id,
            'reason' => $refund->reason,
            'description' => $refund->description,
            'amount' => $refund->amount,
            'currency' => $refund->currency,
            'type' => $refund->type, // 'refund','appeal'
            'status' => $refund->status, //'pending', 'under_review', 'approved', 'waiting_for_return','processing','completed','rejected','canceled'
            'customer' => [
                'name' => $refund->customer->name,
                'last_name' => $refund->customer->last_name,
                'email' => $refund->customer->email,
            ],
            'appeal' => fractal($refund->children, static::class)->toArray()['data'] ?? [],
            'links' => [
                'index' => route('api.transaction.admin.refund.index'),
                'update' => route('api.transaction.admin.refund.update', ['id' => $refund->id]),
            ]
        ];
    }
}
