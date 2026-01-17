<?php

namespace Core\Transaction\Transformer\User;

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
