<?php

namespace App\Transformers\Tokens;

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

use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class TokensTransformer extends TransformerAbstract
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
    public function transform($token)
    {
        return [
            'id' => $token->id,
            'agent' => $token->name,
            'scope' => implode(",", $token->scopes),
            'revoked' => $token->revoked,
            'expires' => $token->expires_at ? $this->format_date($token->expires_at) : null,
            'created' => $token->created_at ? $this->format_date($token->created_at) : null,
            'updated' => $token->updated_at ? $this->format_date($token->updated_at) : null,
            'links' => [
                'index' => route('tokens.index'),
                'store' => route('tokens.store'),
                'destroy' => route('tokens.destroy', ['token' => $token->id]),
                'destroyAll' => route('tokens.destroyAll')
            ]
        ];
    }
}
