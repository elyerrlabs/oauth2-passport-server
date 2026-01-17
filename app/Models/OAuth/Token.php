<?php

namespace App\Models\OAuth;

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
use Laravel\Passport\Token as PassportToken;

class Token extends PassportToken
{
    use Asset;

    /**
     * Getter for Created at field
     * @param mixed $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return $this->format_date($value);
    }

    /**
     * Getter for updated at field
     * @param mixed $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        return $this->format_date($value);
    }

    /**
     * Getter for expires at field
     * @param mixed $value
     * @return string
     */
    public function getExpiresAtAttribute($value)
    {
        return $this->format_date($value, 'Y-m-d H:i');
    }

    /**
     * Session
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<OAuthSessionToken, Token>
     */
    public function oauthSessionToken()
    {
        return $this->hasOne(OAuthSessionToken::class, 'oauth_access_token_id', 'id');
    }
}
