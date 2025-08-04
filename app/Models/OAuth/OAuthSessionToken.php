<?php

namespace App\Models\OAuth;

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
