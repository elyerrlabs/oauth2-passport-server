<?php
namespace App\Models\Subscription;

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

use App\Models\Master;
use App\Models\User\User;
use App\Models\Subscription\Transaction;
use App\Transformers\Subscription\PackageTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Master
{
    use HasFactory;

    /**
     * table name
     * @var string
     */
    public $table = "packages";

    /**
     * Transformer 
     * @var 
     */
    public $transformer = PackageTransformer::class;

    /**
     * Fillable
     * @var array
     */
    protected $fillable = [
        'status',
        'start_at',
        'end_at',
        'cancellation_at',
        'last_renewal_at',
        'is_recurring',
        'transaction_code',
        'meta',
        'user_id',
    ];

    protected $casts = [
        'meta' => 'array',
        'is_recurring' => 'boolean',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'cancellation_at' => 'datetime',
        'last_renewal_at' => 'datetime',
    ];

    /**
     * Get the user that owns the UserSubscription
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The last transaction
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lastTransaction()
    {
        return $this->hasOne(Transaction::class, 'code', 'transaction_code');
    }

    /**
     * transactions
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * check if it the transaction is cancelled
     * @return bool
     */
    public function isCancelled()
    {
        return $this->cancellation_at ? true : false;
    }
}
