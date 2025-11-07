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

use Core\Transaction\Model\Plan;
use Core\User\Model\Scope;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class PlanScopeTransformer extends TransformerAbstract
{
    use Asset;

    /**
     * Plan 
     * @var 
     */
    public $plan;

    public function __construct(Plan $plan)
    {
        $this->plan = $plan;
    }

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
            'public' => $scope->public ? true : false,
            'active' => $scope->active ? true : false,
            'gsr_id' => $scope->getGsrId(),
            'api_key' => $scope->api_key,
            'web' => $scope->web,
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
            'created' => $this->format_date($scope->created_at),
            'updated' => $this->format_date($scope->updated_at),
            'links' => [
                'revoke' => route('transaction.admin.plans.scopes.revoke', ['plan' => $this->plan->id, 'scope' => $scope->id]),
            ]
        ];
    }
}
