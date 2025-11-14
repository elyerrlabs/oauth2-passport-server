<?php

namespace App\Http\Controllers\Web\OAuth;

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
