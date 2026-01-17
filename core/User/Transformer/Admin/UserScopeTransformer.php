<?php
namespace Core\User\Transformer\Admin;

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
 
use Core\User\Model\UserScope;
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
                'index' => route('user.admin.users.scopes.index', ['user' => $data->user_id]),
                'history' => route('user.admin.users.scopes.history', ['user' => $data->user_id]),
                'assign' => route('user.admin.users.scopes.assign', ['user' => $data->user_id]),
                'revoke' => route('user.admin.users.scopes.revoke', ['user' => $data->user_id, 'scope' => $data->id]),
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
