<?php

namespace App\Models\OAuth\Bridge;

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

use Laravel\Passport\Passport; 
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use App\Repositories\OAuth\Server\Grant\OAuthSessionTokenRepository;

class AuthCodeRepository extends \OpenIDConnect\Repositories\AuthCodeRepository
{

    /**
     * Session token repository
     * @var 
     */
    public $oauth_session_repository;

    public function __construct(OAuthSessionTokenRepository $oAuthSessionTokenRepository)
    {
        $this->oauth_session_repository = $oAuthSessionTokenRepository;
    }
    /**
     * {@inheritdoc}
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity): void
    {
        $identifier = $authCodeEntity->getIdentifier();

        $attributes = [
            'id' => $identifier,
            'user_id' => $authCodeEntity->getUserIdentifier(),
            'client_id' => $authCodeEntity->getClient()->getIdentifier(),
            'scopes' => null,
            'revoked' => false,
            'expires_at' => $authCodeEntity->getExpiryDateTime(),
        ];
       
        Passport::authCode()->forceFill($attributes)->save();

        // Set code to the current session
        $this->oauth_session_repository->create([
            'session_id' => session()->getId(),
            'oauth_auth_code_id' => $identifier
        ]);
    }
}
