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
 
 
use Core\Transaction\Model\Package;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;
use Core\User\Transformer\Admin\UserTransformer;
use Core\Transaction\Transformer\Admin\TransactionTransformer; 

class PackageTransformer extends TransformerAbstract
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
    public function transform(Package $package)
    {
        return [
            'id' => $package->id,
            'start_at' => $this->format_date($package->start_at),
            'end_at' => $this->format_date($package->end_at),
            'cancellation_at' => $this->format_date($package->cancellation_at),
            'last_renewal_at' => $this->format_date($package->last_renewal_at),
            'is_recurring' => $package->is_recurring,
            'status' => $package->status,
            'transaction' => $package->transform($package->lastTransaction, TransactionTransformer::class),
            'transactions' => $package->transform($package->transactions, TransactionTransformer::class),
            'meta' => $package->meta, // save plan
            'user' => $package->transform($package->user, UserTransformer::class),
            'create' => $this->format_date($package->created_at),
            'updated' => $this->format_date($package->updated),
        ];
    }



    /**
     * Return the original attribute
     * @param mixed $index
     * @return string|null
     */
    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'price' => 'price',
            'currency' => 'currency',
            'billing_period' => 'billing_period',
            'payment_method' => 'payment_method',
            'start_at' => 'start_at',
            'end_at' => 'end_at',
            'cancellation_at' => 'cancellation_at',
            'last_renewal_at' => 'last_renewal_at',
            'meta' => 'meta',
            'is_recurring' => 'is_recurring',
            'status' => 'status',
            'create' => 'created_at',
            'updated' => 'updated_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
