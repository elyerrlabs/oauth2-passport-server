<?php
namespace App\Http\Controllers\Web\Admin\Subscription;

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


use Illuminate\Http\Request;
use App\Repositories\ScopeRepository;
use App\Http\Controllers\WebController;

class ScopeController extends WebController
{
    /**
     * Repository
     * @var ScopeRepository
     */
    public $repository;

    /**
     * Construct
     * @param \App\Repositories\ScopeRepository $scopeRepository
     */
    public function __construct(ScopeRepository $scopeRepository)
    {
        parent::__construct();
        $this->repository = $scopeRepository;
        $this->middleware('userCanAny:administrator:scope:full,administrator:scope:view')->only('index');
        $this->middleware('wants.json');
    }

    /**
     * Show the all scope
     * @param \Illuminate\Http\Request $request
     */
    public function index(Request $request)
    {
        return $this->repository->search($request);
    }
}
