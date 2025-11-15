<?php
namespace Core\User\Http\Controllers\Admin;

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


use Core\User\Services\ScopeService;
use Core\User\Transformer\Admin\ScopeTransformer;
use Illuminate\Http\Request;
use Core\User\Repositories\ScopeRepository;
use App\Http\Controllers\WebController;

class ScopeController extends WebController
{
    /**
     * Repository
     * @var  ScopeService
     */
    public $scopeService;

    /**
     * Scope service
     * @param \Core\User\Services\ScopeService $scopeService
     */
    public function __construct(ScopeService $scopeService)
    {
        parent::__construct();
        $this->scopeService = $scopeService;
        $this->middleware('userCanAny:administrator:scope:full,administrator:scope:view')->only('index');
        $this->middleware('wants.json');
    }

    /**
     * Show the all scope for user only public and active
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<\Core\User\Model\Scope>
     */
    public function index(Request $request)
    {
        $scopes = $this->scopeService->searchForUser($request)->get();

        return $this->showAll($scopes, ScopeTransformer::class, 200, false);
    }
}
