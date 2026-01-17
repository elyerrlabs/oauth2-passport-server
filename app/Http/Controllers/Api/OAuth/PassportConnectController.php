<?php
namespace App\Http\Controllers\Api\OAuth;

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

use Illuminate\Http\Request;
use App\Repositories\Traits\Scopes;
use App\Http\Controllers\ApiController;
use Core\User\Transformer\User\OpenIDTransformer; 
use App\Repositories\OAuth\Server\Grant\OAuthSessionTokenRepository;

class PassportConnectController extends ApiController
{

    use Scopes;

    public function __construct()
    {
        //headers
        $scopes = request()->header('X-SCOPES');

        parent::__construct();

        $this->middleware('scope:' . $scopes)->only('check_scope_any');
        $this->middleware('scopes:' . $scopes)->only('check_scope_all');

        if (isset($scopes)) {
            $this->middleware('client:' . $scopes)->only('check_client_credentials');
        } else {
            $this->middleware('client')->only('check_client_credentials');
        }
    }

    /**
     * Gateway to verify if a user is authenticated. 
     * This request includes Authorization headers.
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function check_authentication(Request $request)
    {
    }

    /**
     * Gateway to verify if at least one scope is present. 
     * This request includes Authorization and X-SCOPES headers.
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function check_scope_any(Request $request)
    {
    }

    /**
     * Gateway to verify if all scopes are present. 
     * This request includes Authorization and X-SCOPES headers.
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function check_scope_all(Request $request)
    {
    }

    /**
     * Gateway to verify if client credentials are correct. 
     * This request includes Authorization header and optionally X-SCOPES header.
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function check_client_credentials(Request $request)
    {
    }

    /**
     * Gateway to verify whether the user is authorized to perform an action.
     * This request includes Authorization header and optionally X-SCOPE header.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function user_can(Request $request)
    {
        $scope = $request->header('X-SCOPE');

        if (request()->user()->tokenCan($scope)) {
            return response(null, 200);
        }

        return response(null, 403);
    }

    /**
     * Gateway to retrieve information about the auth user
     * @param \Illuminate\Http\Request $request
     */
    public function authenticated(Request $request)
    {
        return fractal(
            auth()->user(),
            OpenIDTransformer::class
        )->toArray()['data'];
    }

    /**
     * Gateway close session
     * @param \Illuminate\Http\Request $request
     * @param \App\Repositories\OAuth\Server\Grant\OAuthSessionTokenRepository $oAuthSessionTokenRepository
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function revokeAuthorization(Request $request, OAuthSessionTokenRepository $oAuthSessionTokenRepository)
    {
        $token = auth()->user()->token();

        $session = $oAuthSessionTokenRepository->findToken($token->id);

        // Revoke token with session
        if (!empty($session)) {
            $oAuthSessionTokenRepository->destroyTokenSession($session->getSessionId());
        } else {
            $token->revoke();
        }

        return $this->message(__('Session finished successfully'), 200);
    }

    /**
     * Gateway to retrieve the all scopes available for the auth user
     * @return \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Scope>|\Illuminate\Support\Collection<int, \Laravel\Passport\Scope>
     */
    public function access()
    {
        return $this->scopes(false);
    }
}
