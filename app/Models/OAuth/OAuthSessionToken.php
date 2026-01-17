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

use App\Models\Master;
use App\Models\Setting\Session;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OAuthSessionToken extends Master
{
    use HasFactory;

    /**
     * Table name
     * @var string
     */
    public $table = "oauth_session_tokens";

    protected $fillable = [
        'session_id',
        'oauth_access_token_id',
        'oauth_auth_code_id'
    ];


    /**
     * Retrieve the session id
     */
    public function getSessionId()
    {
        return $this->session_id;
    }

    /**
     * Retrieve the access token id
     */
    public function getAccessTokenId()
    {
        return $this->oauth_access_token_id;
    }

    /**
     * Retrieve the code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Belong to the session
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Session, OAuthSessionToken>
     */
    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * Token
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<Token, OAuthSessionToken>
     */
    public function token()
    {
        return $this->hasOne(Token::class, 'id', 'oauth_access_token_id');
    }
}
