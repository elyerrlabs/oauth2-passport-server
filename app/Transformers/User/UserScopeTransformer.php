<?php
namespace App\Transformers\User;

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

use App\Models\User\UserScope;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class UserScopeTransformer extends TransformerAbstract
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
    public function transform(UserScope $data)
    {
        return [
            'id' => $data->id,
            'expiration_date' => $data->end_date,
            'package_id' => $data->package_id,
            'status' => $data->revoked(),
            'end_date' => $this->format_date($data->end_date),
            'scope' => [
                'id' => $data->scope->id,
                'gsr_id' => $data->scope->getGsrID(),
                'public' => $data->scope->public,
                'active' => $data->scope->active,
                'service' => [
                    'id' => $data->scope->service->id,
                    'name' => $data->scope->service->name,
                    'slug' => $data->scope->service->slug,
                    'description' => $data->scope->service->description,
                    'system' => $data->scope->service->system ? true : false,
                    'group' => [
                        'id' => $data->scope->service->group->id,
                        'name' => $data->scope->service->group->name,
                        'description' => $data->scope->service->group->description,
                    ]
                ],
                'role' => [
                    'id' => $data->scope->role->id,
                    'name' => $data->scope->role->name,
                    'slug' => $data->scope->role->slug,
                    'description' => $data->scope->role->description
                ],
            ],
            'created_at' => $this->format_date($data->created_at),
            'updated_at' => $this->format_date($data->updated_at),
            'links' => [
                'index' => route('admin.users.scopes.index', ['user' => $data->user_id]),
                'history' => route('admin.users.scopes.history', ['user' => $data->user_id]),
                'assign' => route('admin.users.scopes.assign', ['user' => $data->user_id]),
                'revoke' => route('admin.users.scopes.revoke', ['user' => $data->user_id, 'scope' => $data->id]),
            ]
        ];
    }

    /**
     * Return the original keys
     * @param mixed $index
     * @return string|null
     */
    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'scope_id' => 'scope_id',
            'user_id' => 'user_id',
            'end_date' => 'end_date',
            'package_id' => 'package_id',
            'created' => "created_at",
            'updated' => "updated_at",
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
