<?php

namespace Core\Transaction\Model;

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

use App\Models\Master;
use Core\Transaction\Model\User;
use App\Models\Common\File; 
use App\Repositories\Contracts\Dynamic; 
use Core\Transaction\Model\Transaction;

class Refund extends Master
{

    use Dynamic;

    public $tag = "common_refunds";

    protected $table = 'refunds';

    protected $fillable = [
        'reason',
        'description',
        'amount',
        'currency',
        'type', // 'refund','appeal'
        'status',
        'user_id',
        'assigned_to',
        'assigned_by',
        'parent_id',
        'transaction_id',
    ];

    /**
     * Statuses
     * @return string[]
     */
    public static function statuses()
    {
        return [
            'pending',
            'processing',
            'under_review',
            'approved',
            'waiting_for_return',
            'refunding',
            'completed',
            'rejected',
            'canceled'
        ];
    }

    /**
     * Type of refund
     * @return string[]
     */
    public static function types()
    {
        return [
            'refund',
            'appeal'
        ];
    }

    /**
     * Validate status
     * @param string $status
     * @return void
     */
    public function validateStatus(string $status)
    {
        $list = static::statuses();
        unset($list['waiting_for_return']);

        //  continue
    }

    /**
     * Amount
     * @param mixed $value
     * @return void
     */
    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = str_replace([',', '.'], '', $value);
    }

    /**
     * Refund
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function refund()
    {
        return $this->belongsTo(static::class);
    }

    /**
     * Has one appeal
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function appeal()
    {
        return $this->hasOne(static::class, 'parent_id');
    }

    /**
     * Get the user who owns the refund.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, Refund>
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the admin who handled or processed the refund.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, Refund>
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Assigned by
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Files
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<File, Refund>
     */
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    /**
     * Transaction
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    /**
     * Parent transaction
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentTransaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
