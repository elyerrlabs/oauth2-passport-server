<?php

namespace App\Repositories\OAuth;

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

use App\Models\OAuth\Token;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Auth\Authenticatable;

class TokenRepository
{

    /**
     * Construct
     * @param \App\Repositories\OAuth\Token $token
     */
    public function __construct(private Token $token)
    {
    }

    /**
     * Get a token by the given user ID and token ID.
     * @param  \Laravel\Passport\Contracts\OAuthenticatable  $user
     */
    public function findForUser(string $id, Authenticatable $user): ?Token
    {
        $token = $this->token->where('user_id', $user->id)
            ->whereHas('client', function ($query) {
                $query->whereJsonContains('grant_types', 'personal_access');
            })->where('revoked', false)
            ->where('expires_at', '>', Date::now())
            ->find($id);

        return $token;
    }

    /**
     *  Get the token instances for the given user ID.
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     */
    public function forUser(Authenticatable $user): Builder
    {
        $query = $this->token->query();

        $query->where('user_id', $user->id)
            ->whereHas('client', function ($query) {
                $query->whereJsonContains('grant_types', 'personal_access');
            })->where('revoked', false)
            ->where('expires_at', '>', Date::now());

        return $query;
    }
}
