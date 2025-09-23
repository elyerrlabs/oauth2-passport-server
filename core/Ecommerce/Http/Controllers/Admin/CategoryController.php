<?php

namespace Core\Ecommerce\Http\Controllers\Admin;

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

use App\Http\Middleware\WantsJsonHeader;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use App\Rules\BooleanRule;
use Illuminate\Http\Request;
use App\Rules\UndefinedValues;
use Stevebauman\Purify\Facades\Purify;
use App\Http\Controllers\WebController;
use Core\Ecommerce\Repositories\ProductRepository;
use Core\Ecommerce\Repositories\CategoryRepository;
use Core\Ecommerce\Http\Requests\Category\StoreRequest;

class CategoryController extends WebController
{
    /**
     * Category
     * @var CategoryRepository
     */
    private $repository;

    /**
     * Product
     * @var ProductRepository
     */
    private $product_repository;


    public function __construct(CategoryRepository $categoryRepository, ProductRepository $productRepository)
    {
        parent::__construct();
        $this->repository = $categoryRepository;
        $this->product_repository = $productRepository;
        $this->middleware('userCanAny:administrator:ecommerce:full, administrator:ecommerce:view')->only('index');
        $this->middleware('userCanAny:administrator:ecommerce:full, administrator:ecommerce:store')->only('store');
        $this->middleware('userCanAny:administrator:ecommerce:full, administrator:ecommerce:delete')->only('destroy');
    }

    /**
     * Show all resources
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {

            $request->merge([
                'tag' => $this->product_repository->getTag()
            ]);

            $query = $this->repository->search($request);

            return $this->showAllByBuilder(
                $query,
                $this->repository->transformer
            );
        }
        return Inertia::render('Core/Ecommerce/Admin/Category/Index', [
            'routes' => [
                'index' => route('ecommerce.admin.categories.index'),
                'create' => route('ecommerce.admin.categories.create')
            ],
            'ecommerce_menus' => resolveInertiaRoutes(config('menus.ecommerce_menus'))
        ])->rootView('ecommerce');
    }

    /**
     * Show detail
     * @param string $category_slug
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Core/Ecommerce/Admin/Category/Create', [
            'routes' => [
                'index' => route('ecommerce.admin.categories.index'),
                'create' => route('ecommerce.admin.categories.create'),
                'store' => route('ecommerce.admin.categories.store'),
            ],
            'ecommerce_menus' => resolveInertiaRoutes(config('menus.ecommerce_menus'))
        ])->rootView('ecommerce');
    }

    /**
     * Edit
     * @param Request $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function edit(Request $request, string $id)
    {
        $model = $this->repository->find($id);

        return Inertia::render('Core/Ecommerce/Admin/Category/Create', [
            'model' => fractal($model, $this->repository->transformer)->toArray()['data'],
            'routes' => [
                'index' => route('ecommerce.admin.categories.index'),
                'create' => route('ecommerce.admin.categories.create'),
                'store' => route('ecommerce.admin.categories.store'),
            ],
            'ecommerce_menus' => resolveInertiaRoutes(config('menus.ecommerce_menus'))
        ])->rootView('ecommerce');
    }

    /**
     * Create new resource
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $request->merge([
            'description' => Purify::clean($request->description),
        ]);

        if (!empty($request->filled('id')) && $this->repository->find($request->id)) {
            $model = $this->repository->update($request->id, $request->toArray());
            return $this->showOne($model, $this->repository->transformer);
        }

        $request->merge([
            'slug' => normalizeSlug($request->name),
            'tag' => $this->product_repository->getTag(),
            'description' => Purify::clean($request->description),
        ]);

        $model = $this->repository->create($request->toArray());

        return $this->showOne($model, $this->repository->transformer, 201);
    }


    /**
     * Destroy resource
     * @param string $category_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $category_id)
    {
        $model = $this->repository->delete($category_id);
        return $this->showOne($model, $this->repository->transformer);
    }
}
