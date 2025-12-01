<?php

namespace App\Models\Common;

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

use App\Models\Master;
use Core\User\Model\User;
use App\Repositories\Contracts\Dynamic;
use Illuminate\Database\Eloquent\Model;

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
        'status', //'pending', 'under_review', 'approved', 'waiting_for_return','processing',            'completed',                'rejected',                'canceled'
        'user_id',
        'handled_id',
        'parent_id'
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
     * refundable
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo<Model, Refund>
     */
    public function refundable()
    {
        return $this->morphTo();
    }

    public function refund()
    {
        return $this->belongsTo(static::class);
    }

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
    public function handledBy()
    {
        return $this->belongsTo(User::class, 'handled_id');
    }

    /**
     * Files
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<File, Refund>
     */
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
