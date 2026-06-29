<?php

namespace App\Repositories\OAuth\OpenID;

use OpenIDConnect\Interfaces\IdentityEntityInterface;
use OpenIDConnect\Interfaces\IdentityRepositoryInterface;

class IdentityRepository implements IdentityRepositoryInterface
{
    public function getByIdentifier(string $identifier): IdentityEntityInterface
    {
        $className = \App\Repositories\OAuth\OpenID\IdentityEntity::class;

        $identityEntity = new $className();
        $identityEntity->setIdentifier($identifier);
        return $identityEntity;
    }
}
