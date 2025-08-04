<?php

namespace App\Transformers\OAuth;

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
 * 
 * SPDX-License-Identifier: LicenseRef-NC-Open-Source-Project
 */

use App\Models\OAuth\Client;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class ClientTransformer extends TransformerAbstract
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
    public function transform(Client $client)
    {
        return [
            "id" => $client->id,
            "name" => $client->name,
            "secret" => $client->plainSecret ?: $client->secret,
            "confidential" => $client->secret ? true : false,
            "provider" => $client->provider,
            "redirect_uris" => $client->redirect_uris,
            "redirect" => implode(", ", $client->redirect_uris),
            "grant_types" => implode(", ", $client->grant_types),
            "discovery_uri" => $client->openid_connect_configuration,
            "revoked" => $client->revoked,
            "created_at" => $this->format_date($client->created_at),
            "updated_at" => $this->format_date($client->updated_at),
            'links' => [
                'index' => route('passport.clients.index'),
                'store' => route('passport.clients.store'),
                'update' => route('passport.clients.update', ['client_id' => $client->id]),
                'destroy' => route('passport.clients.destroy', ['client_id' => $client->id])
            ]
        ];
    }

    /**
     * Transform the original attribute
     * @param mixed $index
     * @return string|null
     */
    public static function getOriginalAttributes($index)
    {
        $attributes = [
            "id" => "id",
            "user_id" => "user_id",
            "name" => "name",
            "provider" => "provider",
            "redirect" => "redirect",
            "personal_access_client" => "personal_access_client",
            "password_client" => "password_client",
            "revoked" => "revoked",
            "created_at" => "created_at",
            "updated_at" => "updated_at",
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

}
