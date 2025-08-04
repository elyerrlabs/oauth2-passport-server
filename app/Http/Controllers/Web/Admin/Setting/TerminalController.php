<?php

namespace App\Http\Controllers\Web\Admin\Setting;

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
use App\Http\Controllers\WebController;
use App\Repositories\TerminalRepository;

class TerminalController extends WebController
{
    /**
     * Terminal repository
     * @var TerminalRepository
     */
    public $repository;

    public function __construct(TerminalRepository $terminalRepository)
    {
        parent::__construct();
        $this->repository = $terminalRepository;
        $this->middleware('userCanAny:administrator:terminal:full,administrator:user:view')->only('index');
        $this->middleware('userCanAny:administrator:terminal:full,administrator:user:execute')->only('show');
    }

    /**
     * Show resources
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return $this->repository->search($request);
        }

        return Inertia::render('Terminal/Index', [
            'route' => route('admin.terminals.index')
        ]);
    }

    /**
     * Execute new command
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request)
    {
        $request->validate([
            'command' => ['required', 'string', 'max:255']
        ]);

        return $this->repository->create($request->toArray());
    }
}
