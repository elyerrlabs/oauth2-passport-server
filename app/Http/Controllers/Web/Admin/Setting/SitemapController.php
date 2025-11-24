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

use App\Http\Controllers\WebController;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use App\Services\SiteMapService;
use Illuminate\Http\Request;

final class SitemapController extends WebController
{

    /**
     * Sitemap Service
     * @var SiteMapService
     */
    private $sitemapService;

    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware("userCanAny:administrator:seo:full,administrator:seo:view")->only('index');
        $this->middleware("userCanAny:administrator:seo:full,administrator:seo:create")->only('store');
        $this->middleware("userCanAny:administrator:seo:full,administrator:seo:destroy")->only('delete');
        $this->middleware("userCanAny:administrator:seo:full,administrator:seo:reset")->only('reset');
        $this->sitemapService = app(SiteMapService::class);

    }

    /**
     * Site map 
     * @return \Inertia\Response
     */
    public function index()
    {
        $data = $this->sitemapService->listRoutes()->toArray();

        return Inertia::render('Sitemap/Index', [
            'data' => $data,
            'routes' => [
                'index' => route('admin.sitemaps.index'),
                'store' => route('admin.sitemaps.store'),
                'reset' => route('admin.sitemaps.reset'),
            ],
            'menus' => resolveInertiaRoutes(config('menus.seo_menus')),
        ]);
    }


    public function metaForm()
    {
        return Inertia::render('Sitemap/Meta', [
            'data' => $this->sitemapService->getMetaData(),
            'routes' => [
                'index' => route('admin.sitemaps.meta.form'),
                'store' => route('admin.sitemaps.meta.update'),
            ],
            'menus' => resolveInertiaRoutes(config('menus.seo_menus')),
        ]);
    }

    /**
     * Update meta data
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateMetaForm(Request $request)
    {
        $this->validate($request, [
            'meta' => 'required',
        ]);

        $this->sitemapService->updateMetaData($request);

        return redirect()->back();
    }

    /**
     * Add new route
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'url' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!filter_var($value, FILTER_VALIDATE_URL)) {
                        return $fail("The $attribute must be a valid URL.");
                    }

                    $scheme = parse_url($value, PHP_URL_SCHEME);

                    if (!in_array($scheme, ['http', 'https', 'ftp'])) {
                        return $fail("Only http, https or ftp protocols are allowed.");
                    }
                }
            ],
            'image' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value && !filter_var($value, FILTER_VALIDATE_URL)) {
                        return $fail("The $attribute must be a valid URL.");
                    }
                }
            ],
            'changefreq' => [
                'nullable',
                Rule::in(['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'])
            ],
            'priority' => [
                'nullable',
                'numeric',
                'between:0.1,1.0'
            ],
        ]);

        $this->sitemapService->register(
            'sitemap',
            $request->url,
            $request->image,
            $request->changefreq ?? 'weekly',
            $request->priority ?? 0.5
        );

        return redirect()->back();
    }

    /**
     * Destroy
     * @param string $url
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(string $url)
    {
        $this->sitemapService->remove($url);

        return redirect()->back();
    }

    /**
     * Reset sitemap
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset()
    {
        $this->sitemapService->reset();
        return redirect()->back();
    }
}
