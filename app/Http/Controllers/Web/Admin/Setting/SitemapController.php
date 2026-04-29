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
     * Construct
     */
    public function __construct(protected SiteMapService $sitemapService)
    {
        parent::__construct();
        $this->middleware("userCanAny:administrator:seo:full,administrator:seo:view")->only('index', 'metaForm', 'robotForm');
        $this->middleware("userCanAny:administrator:seo:full,administrator:seo:create")->only('store', 'updateMetaForm', 'updateMeta', 'updateRobot');
        $this->middleware("userCanAny:administrator:seo:full,administrator:seo:destroy")->only('delete');
        $this->middleware("userCanAny:administrator:seo:full,administrator:seo:reset")->only('reset');
    }

    /**
     * List sitemap
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data = $this->sitemapService->listRoutes()->toArray();

        return view('admin.sitemap.index', compact('data'));
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
                        return $fail(__("Only http, https or ftp protocols are allowed."));
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

        return redirect()->back()->with("status", __('Sitemap updated succesfully'));
    }

    /**
     * Destroy
     * @param string $url
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(string $url)
    {
        $this->sitemapService->remove($url);

        return redirect()->back()->with("status", __('Page deleted succesfully'));
    }

    /**
     * Reset sitemap
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset()
    {
        $this->sitemapService->reset();
        return redirect()->back()->with("status", __('Sitemap reset successfully'));
        ;
    }

    /**
     * Robot form
     * @return \Illuminate\Contracts\View\View
     */
    public function robotForm()
    {
        $content = $this->sitemapService->getRobotData();
        return view('admin.sitemap.robot', compact('content'));
    }

    /**
     * Updated robot.txt
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRobot(Request $request)
    {
        $this->validate($request, [
            'content' => 'required',
        ]);

        $this->sitemapService->updateRobotData($request);

        return redirect()->back()->with('status', __('Content updated successfully'));
    }

    /**
     * show form favicon
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function faviconForm()
    {
        $favicon = $this->sitemapService->getFaviconData();

        return view('admin.sitemap.favicon', compact('favicon'));
    }

    /**
     * Update favicon
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateFavicon(Request $request)
    {
        $request->validate([
            'favicon' => 'required|file|mimes:ico,png',
        ]);

        $this->sitemapService->updateFavicon($request);

        return redirect()->back()->with('status', __('Favicon updated successfully'));
    }
}
