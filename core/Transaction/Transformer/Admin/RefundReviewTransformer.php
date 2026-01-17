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

use App\Transformers\File\FilePrivateTransformer;
use Core\Transaction\Model\Refund;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class RefundReviewTransformer extends TransformerAbstract
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
            'transaction' => fractal($refund->parentTransaction, TransactionTransformer::class)->toArray()['data'] ?? [],
            'transactionable' => fractal($refund->transaction, TransactionTransformer::class)->toArray()['data'] ?? [],
            'appeal' => fractal($refund->children, static::class)->toArray()['data'] ?? [],
            'files' => fractal($refund->files, new FilePrivateTransformer($refund->id))->toArray()['data'] ?? [],
            'created' => $this->format_date($refund->created_at),
            'updated' => $this->format_date($refund->updated_at),
            'web' => [
                'index' => route('transaction.admin.refunds.review.index'),
                'show' => route('transaction.admin.refunds.review.show', ['refund' => $refund->id]),
                'update' => route('transaction.admin.refunds.review.update', ['refund' => $refund->id]),
            ]
        ];
    }
}
