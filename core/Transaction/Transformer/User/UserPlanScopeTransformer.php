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
 
use Core\User\Model\Scope;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class UserPlanScopeTransformer extends TransformerAbstract
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
    public function transform(Scope $scope)
    {
        return [
            'id' => $scope->id,
            'gsr_id' => $scope->getGsrId(),
            'service' => [
                'id' => $scope->service->id,
                'name' => $scope->service->name,
                'slug' => $scope->service->slug,
                'description' => $scope->service->description,
                'system' => $scope->service->system ? true : false,
                'group' => [
                    'id' => $scope->service->group->id,
                    'name' => $scope->service->group->name,
                    'description' => $scope->service->group->description,
                ]
            ],
            'role' => [
                'id' => $scope->role->id,
                'name' => $scope->role->name,
                'slug' => $scope->role->slug,
                'description' => $scope->role->description
            ],
        ];
    }
}
