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

use DateTime; 
use Laravel\Passport\Passport;
use Laravel\Passport\Events\AccessTokenCreated; 
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;

class AccessTokenRepository extends \OpenIDConnect\Repositories\AccessTokenRepository
{

    /**
     * {@inheritdoc}
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity): void
    {
        // Retrieve the client id of the token 
        $clientId = $accessTokenEntity->getClient()->getIdentifier();

        // Generate data to create token
        $data = [
            'id' => $accessTokenEntity->getIdentifier(),
            'user_id' => $accessTokenEntity->getUserIdentifier(),
            'client_id' => $clientId,
            'revoked' => false,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
            'expires_at' => $accessTokenEntity->getExpiryDateTime(),
        ];
 
        // Checking api key only personal access token
        if (in_array('personal_access', $accessTokenEntity->getClient()->grantTypes)) {
            $data['scopes'] = $accessTokenEntity->getScopes();
        } else {
            $data['scopes'] = [];
        }

        Passport::token()->forceFill($data)->save();

        // Dispatch token
       /* $this->events->dispatch(new AccessTokenCreated(
            $accessTokenEntity->getIdentifier(),
            $accessTokenEntity->getUserIdentifier(),
            $accessTokenEntity->getClient()->getIdentifier()
        ));*/
    }
}
