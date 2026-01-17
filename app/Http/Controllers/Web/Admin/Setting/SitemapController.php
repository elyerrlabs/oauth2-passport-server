<?php

namespace App\Http\Controllers\Web\Admin\Setting;


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
        $this->middleware("userCanAny:administrator:seo:full,administrator:seo:view")->only('index', 'metaForm', 'robotForm');
        $this->middleware("userCanAny:administrator:seo:full,administrator:seo:create")->only('store', 'updateMetaForm', 'updateMeta', 'updateRobot');
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
    public function updateMeta(Request $request)
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
            'pages',
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

    /**
     * Show robot form editor
     * @return \Inertia\Response
     */
    public function robotForm()
    {
        return Inertia::render('Sitemap/Robot', [
            'data' => $this->sitemapService->getRobotData(),
            'routes' => [
                'index' => route('admin.sitemaps.robot.form'),
                'store' => route('admin.sitemaps.robot.update'),
            ],
            'menus' => resolveInertiaRoutes(config('menus.seo_menus')),
        ]);
    }

    /**
     * Updated robot.txt
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRobot(Request $request)
    {
        $this->validate($request, [
            'meta' => 'required',
        ]);

        $this->sitemapService->updateRobotData($request);

        return redirect()->back();
    }
}
