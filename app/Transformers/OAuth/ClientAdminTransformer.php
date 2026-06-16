<?php

namespace App\Transformers\OAuth;

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

use App\Models\OAuth\Client; 
use League\Fractal\TransformerAbstract;

class ClientAdminTransformer extends TransformerAbstract
{ 
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
    public function transform(Client $client)
    {
        return [
            "id" => $client->id,
            "name" => $client->name,
            "secret" => $client->plainSecret ?: $client->secret,
            "confidential" => $client->secret ? true : false,
            "provider" => $client->provider,
            "created_by" => [
                'name' => $client->owner->name ?? null,
                'email' => $client->owner->email ?? null
            ],
            "redirect_uris" => $client->redirect_uris,
            "redirect" => implode(", ", $client->redirect_uris),
            "grant_types" => implode(", ", $client->grant_types),
            "discovery_uri" => $client->openid_connect_configuration,
            "revoked" => $client->revoked,
            "created_at" => format_date($client->created_at),
            "updated_at" => format_date($client->updated_at),
            'links' => [
                'index' => route('admin.clients.index'),
                'store' => route('admin.clients.store'),
                'update' => route('admin.clients.update', ['client' => $client->id]),
                'destroy' => route('admin.clients.destroy', ['client' => $client->id])
            ]
        ];
        ;
    }
}
