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

use Core\User\Model\Service;
use Core\User\Transformer\Admin\ServiceScopeTransformer;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class ServiceTransformer extends TransformerAbstract
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
    public function transform(Service $data)
    {
        return [
            'id' => $data->id,
            'name' => $data->name,
            'slug' => $data->slug,
            'description' => $data->description,
            'system' => $data->system ? true : false,
            'visibility' => $data->visibility,
            'group' => [
                'id' => $data->id,
                'name' => $data->group->name,
                'slug' => $data->group->slug,
                'description' => $data->group->description,

            ],
            'scopes' => fractal($data->scopes, ServiceScopeTransformer::class)->toArray()['data'] ?? [],
            'created' => $this->format_date($data->created_at),
            'updated' => $this->format_date($data->updated_at),
            'links' => [
                'index' => route('user.admin.services.index'),
                'store' => route('user.admin.services.store'),
                'show' => route('user.admin.services.show', ['service' => $data->id]),
                'update' => route('user.admin.services.update', ['service' => $data->id]),
                'destroy' => route('user.admin.services.destroy', ['service' => $data->id]),
                'scopes' => route('user.admin.service.scopes.index', ['service' => $data->id])
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
            'name' => "name",
            'slug' => "slug",
            'description' => "service",
            'system' => "system",
            'group_id' => "group_id",
            'created' => "created_at",
            'updated' => "updated_at",
            'visibility' => 'visibility',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
