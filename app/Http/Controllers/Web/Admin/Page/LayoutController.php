<?php

namespace App\Http\Controllers\Web\Admin\Page;

use Illuminate\Http\Request;
use App\Services\Page\PageService;
use App\Http\Controllers\WebController;

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

class LayoutController extends WebController
{
    /**
     * Construct
     * @param PageService $pageService
     */
    public function __construct(public PageService $pageService)
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator:pages:full')->except('update');
    }

    /**
     * Form
     * @param Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function form(Request $request)
    {
        if (!$request->has('layout')) {
            return redirect()->route('admin.layouts.schema', [
                'layout' => 'schema'
            ]);
        }

        $layout = $request->input('layout');

        $content = $this->pageService->loadLayout($layout);

        return view('admin.pages.parts.layout', compact('content', 'layout'));
    }


    /**
     * updated
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $path = $this->pageService->loadLayoutPath($request->input('layout'));

        $this->pageService->updateFile($path, $request->input('content'));

        return back()->with('status', __('Layout updated successfully'));
    }
}
