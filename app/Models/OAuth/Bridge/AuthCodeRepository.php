<?php

namespace App\Models\OAuth\Bridge;

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
