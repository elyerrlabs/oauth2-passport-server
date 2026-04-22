<?php

namespace App\Http\Controllers\Web\Admin\Page;

use App\Http\Controllers\WebController;
use App\Jobs\SitemapIndexJob;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;
use App\Services\Page\PageService;

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

final class PageController extends WebController
{

    public function __construct(protected PageService $pageService)
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator:pages:full');
    }

    /**
     * Index
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $pages = $this->pageService->search($request);
        $pages = $pages->orderBy('updated_at', 'desc')->paginate(15);

        return view('admin.pages.pages', compact('pages'));
    }

    /**
     * Store new page
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $pageId = $page->id ?? null;

        $this->validate($request, [
            'name' => ['required'],
            'slug' => [
                function ($attribute, $value, $fail) use ($pageId) {
                    $query = \DB::table('pages');

                    if ($pageId) {
                        $query->where('id', '!=', $pageId);
                    }

                    if (empty($value)) {
                        $exists = $query
                            ->where(function ($q) {
                                $q->whereNull('slug')
                                    ->orWhere('slug', '');
                            })
                            ->exists();

                        if ($exists) {
                            $fail('Only one page without a slug (landing page) is allowed.');
                        }

                        return;
                    }

                    if ($query->where('slug', $value)->exists()) {
                        $fail('The slug has already been taken.');
                    }
                }
            ]
        ]);

        $page = $this->pageService->create($request->toArray());

        return redirect()->route('admin.pages.edit', ['page' => $page->id])->with('status', __('Page creation successfully'));
    }

    /**
     * Page preview on dev mode
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        $page = $this->pageService->edit($id);

        try {

            return response(view()->file($page->path)->render());

        } catch (\Throwable $e) {

            if ($page->is_draft) {
                return $this->pageService->renderDraftError($e, $page);
            }

            abort(500);
        }
    }

    /**
     * edit page
     * @param string $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(string $id)
    {
        $page = $this->pageService->edit($id);

        return view('admin.pages.edit', compact('page'));
    }


    /**
     * update
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $id)
    {
        $this->pageService->update($id, $request->toArray());

        return back()->with('status', __('Page saved successfully'));
    }

    /**
     * Delete 
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        $this->pageService->delete($id);

        return redirect()->route('admin.pages.index')->with('status', __('Page deleted successfully'));
    }

    /**
     * generate sitemap index
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generateSitemapFile()
    {
        SitemapIndexJob::dispatch();

        return back()->with('status', __('Sitemap index has been generating'));
    }
}
