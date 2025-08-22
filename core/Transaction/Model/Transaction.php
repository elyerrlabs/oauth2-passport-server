<?php
namespace Core\Transaction\Model;

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
use Core\Partner\Model\Partner; 
use Core\Transaction\Model\Package;
use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Core\Transaction\Transformer\Admin\TransactionTransformer;

class Transaction extends Master
{
    use HasFactory;

    public $table = "transactions";

    public $transformer = TransactionTransformer::class;

    protected $fillable = [
        'currency',
        'status',
        'subtotal',
        'total',
        'payment_method',
        'billing_period',
        'renew',
        'session_id',
        'payment_intent_id',
        'payment_url',
        'response', //save response
        'meta', //save package
        'code',
        'package_id',
        'partner_id',
        'partner_commission_rate',
        'payment_method_id'
    ];

    protected $casts = [
        'response' => 'array',
        'renew' => 'boolean',
        'meta' => 'array'
    ];

    /**
     * User
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Plan
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Partner
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
