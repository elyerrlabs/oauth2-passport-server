<?php

namespace App\Transformers\Tokens;

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
