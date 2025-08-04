<?php
namespace App\Http\Controllers\Web\Admin\Manager;

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


use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use App\Repositories\TransactionRepository;

class TransactionManagerController extends WebController
{
    /**
     * Repository
     * @var 
     */
    public $repository;

    /**
     * Constructor
     * @param \App\Repositories\TransactionRepository $transactionRepository
     */
    public function __construct(TransactionRepository $transactionRepository)
    {
        parent::__construct();
        $this->repository = $transactionRepository;
        $this->middleware('userCanAny:administrator:transactions:full, administrator:transactions:view')->only('index');
        $this->middleware('userCanAny:administrator:transactions:full, administrator:transactions:update')->only('activate');
    }

    /**
     * Show the resources
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser|\Inertia\Response
     */
    public function index(Request $request)
    {
        if (request()->wantsJson()) {
            return $this->repository->search($request);
        }

        return Inertia::render("Admin/Transaction/Index", ["route" => route('admin.transactions.index')]);
    }

    /**
     * Activate the transaction
     * @param \App\Models\Subscription\Transaction $transaction
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function activate(string $id)
    {
        return $this->repository->activate($id);
    }
}
