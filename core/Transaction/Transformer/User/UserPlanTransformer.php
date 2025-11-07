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

use Core\Transaction\Transformer\User\UserPlanPriceTransformer;
use Core\Transaction\Model\Plan;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class UserPlanTransformer extends TransformerAbstract
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
    public function transform(Plan $plan)
    {
        return [
            'id' => $plan->id,
            'name' => ucfirst($plan->name),
            'description' => $plan->description,
            'bonus_enabled' => $plan->bonus_enabled ? true : false,
            'bonus_duration' => $plan->bonus_duration,
            'trial_enabled' => $plan->trial_enabled ? true : false,
            'trial_duration' => $plan->trial_duration,
            'created' => $this->format_date($plan->created_at),
            'updated' => $this->format_date($plan->updated_at),
            'prices' => fractal($plan->prices, UserPlanPriceTransformer::class)->toArray()['data'] ?? [],
            'scopes' => fractal($plan->scopes, UserPlanScopeTransformer::class)->toArray()['data'] ?? []
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
            'id' => 'id',
            'name' => 'name',
            'created' => 'created_at',
            'updated' => 'updated_at'
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
