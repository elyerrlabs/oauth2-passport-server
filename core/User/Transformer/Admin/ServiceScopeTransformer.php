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

use Core\User\Model\Scope;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class ServiceScopeTransformer extends TransformerAbstract
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
    public function transform(Scope $data)
    {
        return [
            'id' => $data->id,
            'public' => $data->public ? true : false,
            'active' => $data->active ? true : false,
            'gsr_id' => $data->getGsrID(),
            'api_key' => $data->api_key,
            'web' => $data->web,
            'role' => [
                'id' => $data->role->id,
                'name' => $data->role->name,
                'slug' => $data->role->slug,
                'description' => $data->role->description
            ],
            'created' => $this->format_date($data->created_at),
            'updated' => $this->format_date($data->updated_at),
            'links' => [
                'index' => route('user.admin.service.scopes.index', ['service' => $data->service->id]),
                'assign' => route('user.admin.service.scopes.assign', ['service' => $data->service->id]),
                'revoke' => route('user.admin.services.scopes.revoke', [
                    'service' => $data->service->id,
                    'scope' => $data->id,
                ])
            ]
        ];
    }
}
