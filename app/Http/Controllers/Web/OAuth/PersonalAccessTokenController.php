<?php

namespace App\Http\Controllers\Web\OAuth;

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

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Repositories\Traits\Scopes;
use App\Http\Controllers\WebController;
use App\Repositories\OAuth\TokenRepository;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Elyerr\ApiResponse\Exceptions\ReportError;
use App\Transformers\OAuth\PersonalTokenTransformer;

class PersonalAccessTokenController extends WebController
{
    use Scopes;
    use JsonResponser;

    /**
     * Token repository name
     * @var TokenRepository
     */
    public $repository;

    /**
     * Construct
     * @param \App\Repositories\OAuth\TokenRepository $tokenRepository
     */
    public function __construct(TokenRepository $tokenRepository)
    {
        $this->repository = $tokenRepository;
    }

    /**
     * List token
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function forUser(Request $request)
    {
        $tokens = $this->repository->forUser($request->user());

        if (request()->wantsJson()) {
            return $this->showAllByBuilder($tokens, PersonalTokenTransformer::class);
        }

        return Inertia::render("OAuth2/Web/Personal/Index", [
            'route' => route('passport.personal.tokens.index')
        ]);
    }

    /**
     * Create a new personal access token for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Laravel\Passport\PersonalAccessTokenResult
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'max:255'],
            'scopes' => ['array', Rule::in($this->scopes()->pluck('id')->toArray())],
        ]);

        try {
            return $request->user()->createToken(
                $request->name,
                $request->scopes ?? []
            );
        } catch (\Throwable $th) {
            throw new ReportError(__($th->getMessage()), 404);
        }
    }

    /**
     * Destroy token
     * @param \Illuminate\Http\Request $request
     * @param string $tokenId
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, string $tokenId)
    {
        $token = $this->repository->findForUser(
            $tokenId,
            $request->user()
        );

        if (is_null($token)) {
            throw new ReportError(__('Token cannot be found'), 404);
        }

        $token->revoke();

        return $this->message(__("Token revoked successfully"));
    }


    /**
     * List scopes for api key
     * @return \Illuminate\Support\Collection<int, \Laravel\Passport\Scope>
     */
    public function listScopesForApiToken()
    {
        return $this->scopes(true, false);
    }
}
