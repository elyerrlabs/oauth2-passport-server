<?php

namespace App\Services;

use App\Models\OAuth\Client;
use App\Repositories\OAuth\ClientRepository;
use App\Transformers\OAuth\ClientTransformer;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;

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

final class OauthClientService
{

    public function __construct(
        protected Client $client,
        protected ClientRepository $clientRepository
    ) {
    }


    /**
     * Store a new authorization code grant client.
     *
     * @param  string[]  $redirectUris
     * @param  \Laravel\Passport\Contracts\OAuthenticatable|null  $user
     */
    public function createAuthorizationCodeGrantClient(
        string $name,
        array $redirectUris,
        bool $confidential = true,
        ?Authenticatable $user = null,
        bool $enableDeviceFlow = false,
        bool $private = true
    ): Client {
        $grantTypes = ['authorization_code', 'refresh_token'];

        if ($enableDeviceFlow) {
            $grantTypes[] = 'urn:ietf:params:oauth:grant-type:device_code';
        }

        return $this->create(
            $name,
            $grantTypes,
            $redirectUris,
            null,
            $confidential,
            $user,
            $private
        );
    }

    /**
     * Store a new device authorization grant client.
     *
     * @param  \Laravel\Passport\Contracts\OAuthenticatable|null  $user
     */
    public function createDeviceAuthorizationGrantClient(
        string $name,
        bool $confidential = true,
        ?Authenticatable $user = null
    ): Client {
        return $this->create(
            $name,
            ['urn:ietf:params:oauth:grant-type:device_code', 'refresh_token'],
            [],
            null,
            $confidential,
            $user
        );
    }

    /**
     * Create new client
     * @param string $name
     * @param array $grantTypes
     * @param array $redirectUris
     * @param mixed $provider
     * @param bool $confidential
     * @param mixed $user
     * @param bool $private
     * @return Client
     */
    protected function create(
        string $name,
        array $grantTypes,
        array $redirectUris = [],
        ?string $provider = null,
        bool $confidential = true,
        ?Authenticatable $user = null,
        bool $private = true,
    ): Client {
        $client = Passport::client();
        $columns = $client->getConnection()->getSchemaBuilder()->getColumnListing($client->getTable());

        $attributes = [
            'name' => $name,
            'secret' => $confidential ? Str::random(40) : null,
            'provider' => $provider,
            'private' => $private,
            'revoked' => false,
            ...(in_array('redirect_uris', $columns) ? [
                'redirect_uris' => $redirectUris,
            ] : [
                'redirect' => implode(',', $redirectUris),
            ]),
            ...(in_array('grant_types', $columns) ? [
                'grant_types' => $grantTypes,
            ] : [
                'personal_access_client' => in_array('personal_access', $grantTypes),
                'password_client' => in_array('password', $grantTypes),
            ]),
        ];

        return match (true) {
            !is_null($user) && in_array('user_id', $columns) => $user->clients()->forceCreate($attributes),
            !is_null($user) => $user->oauthApps()->forceCreate($attributes),
            default => $client->newQuery()->forceCreate($attributes),
        };
    }

    /**
     * Find clients for users
     * @param string|int $clientId
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @return string|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\MorphMany<\Laravel\Passport\Client, \Illuminate\Foundation\Auth\User>|\Illuminate\Database\Eloquent\Relations\MorphMany<\Laravel\Passport\Client, \Illuminate\Foundation\Auth\User>[]|null
     */
    public function findForUser(string|int $clientId, Authenticatable $user): ?Client
    {
        return $user->oauthApps()
            ->where('revoked', false)
            ->where('private', false)
            ->find($clientId);
    }

    /**
     * List client for users
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<\Laravel\Passport\Client, \Core\User\Model\User>
     */
    public function findClientsForUser(Request $request)
    {
        $user = $request->user();

        return $user->oauthApps()->where('revoked', false)->where('private', false)->orderBy('created_at');
    }

    /**
     * Create clients for user
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\OAuth\Client
     */
    public function createClientForUser(Request $request): Client
    {
        $user = $request->user();

        $client = $this->createAuthorizationCodeGrantClient(
            $request->name,
            array_map('trim', explode(',', $request->redirect)),
            (bool) $request->input('confidential', true),
            $user,
            false,
            false
        );

        $client->secret = $client->plainSecret;
        $client->openid_connect_configuration = route('openid.discovery');

        return $client->makeVisible('secret');
    }

    /**
     * Update cient for user
     * @param Request $request
     * @param string|int $clientId
     * @throws ReportError
     * @return mixed|\Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Client>|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\MorphMany<\Laravel\Passport\Client, \Core\User\Model\User>|\Illuminate\Database\Eloquent\Relations\MorphMany<\Laravel\Passport\Client, \Core\User\Model\User>[]|\Laravel\Passport\Client|\stdClass
     */
    public function updateClientForUser(Request $request, string|int $clientId): Response|Client
    {
        $user = $request->user();

        $client = $user->oauthApps()->where('revoked', false)->find($clientId);

        if (!$client) {
            throw new ReportError(__('OAuth client can not be found'), 404);
        }

        $this->clientRepository->update($client, $request->name, explode(',', $request->redirect));

        return $client;
    }

    /**
     * Delete client for users
     * @param Request $request
     * @param string|int $clientId
     * @throws ReportError
     * @return string|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\MorphMany<\Laravel\Passport\Client, \Illuminate\Foundation\Auth\User>|\Illuminate\Database\Eloquent\Relations\MorphMany<\Laravel\Passport\Client, \Illuminate\Foundation\Auth\User>[]
     */
    public function deleteClientForUser(Request $request, string|int $clientId)
    {
        $client = $this->findForUser($clientId, auth()->user());

        if (!$client) {
            throw new ReportError(__('Resource not found'), 404);
        }

        $client->delete();

        return $client;
    }

    /**
     * Search client for admins
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder<Client>
     */
    public function searchClientsForAdmin(Request $request)
    {
        return $this->client->query()
            ->where('private', true)
            ->orderBy($request->input('order_by', 'created_at'), $request->input('order_type', 'desc'));
    }

    /**
     * Create clients for admin
     * @param array $data
     * @return Client
     */
    public function createClientForAdmin(array $data)
    {
        $client = $this->createAuthorizationCodeGrantClient(
            $data['name'],
            array_map('trim', explode(',', $data['redirect'])),
            (bool) $data['confidential'] ?? false,
            auth()->user(),
            false,
            true
        );

        $client->secret = $client->plainSecret;

        $client->openid_connect_configuration = route('openid.discovery');

        return $client->makeVisible('secret');
    }

    /**
     * find client for admin
     * @param string $client_id
     * @return Client|TValue|\stdClass|null
     */
    public function findClientForAdmin(string $client_id)
    {
        return $this->client
            ->where('id', $client_id)
            ->where('private', true)
            ->first();
    }

    /**
     * Find client for admin
     * @param string $client_id
     * @throws ReportError
     * @return Client|object|\Illuminate\Database\Eloquent\Model|null
     */
    public function retrieveClientForAdmin(string $client_id)
    {
        $client = $this->findClientForAdmin($client_id);

        if (empty($client)) {
            throw new ReportError(__('Client not be found'), 404);
        }

        return $client;
    }

    /**
     * Update client for admin
     * @param string $client_id
     * @param array $data
     * @return Client|object|\Illuminate\Database\Eloquent\Model|null
     */
    public function updateClientForAdmin(string $client_id, array $data)
    {
        $client = $this->findClientForAdmin($client_id);

        $this->clientRepository->update(
            $client,
            $data['name'],
            explode(',', $data['redirect'])
        );

        return $client;
    }

    /**
     * Revoke client for admin
     * @param string $client_id
     * @throws ReportError
     * @return Client|object|\Illuminate\Database\Eloquent\Model|null
     */
    public function revokeClientForAdmin(string $client_id)
    {
        $client = $client = $this->findClientForAdmin($client_id);

        if (empty($client)) {
            throw new ReportError(__('Client not be found'), 404);
        }

        $client->tokens()->update(['revoked' => true]);

        $client->delete();

        return $client;
    }

    /**
     * Create personal access grant client
     * @param string $name
     * @param mixed $provider
     * @return Client
     */
    public function createPersonalAccessGrantClient(string $name, ?string $provider = null): Client
    {
        return $this->create(
            $name,
            ['personal_access'],
            [],
            $provider,
            true,
            auth()->user()
        );
    }
}
