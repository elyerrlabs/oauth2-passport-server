<?php

namespace App\Http\Controllers\Web\Admin\File;

use Illuminate\Http\Request;
use App\Services\LangService;
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

final class LangController extends WebController
{

    public function __construct(protected LangService $langService)
    {
        parent::__construct();
    }

    /**
     * Index
     * @param Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        $tree = $this->langService->tree();
        $file = "en.json";
        $lang = "json";

        $file_name = $request->input('file') ?? $file;
        $file_content = $this->langService->get($file_name);

        if ($request->filled('file')) {
            $lang = explode('.', $file_name)[1];
        }

        return view('admin.lang.index', compact('tree', 'file_name', 'file_content', 'lang'));
    }

    /**
     * Create new lang
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name' => ['required']]);

        $file = $request->name . ".json";

        $this->langService->createLocale($request->name);

        return redirect()->route('admin.langs.index', compact('file'))->with("status", "New lang created successfully");
    }

    /**
     * Update lang
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'file' => ['required'],
            'content' => ['required'],
        ]);

        $file = $request->input('file');

        $this->langService->put($file, $request->input('content'));

        return redirect()->route('admin.langs.index', compact('file'))->with("status", "Lang updated successfully");

    }

    /**
     * Delete
     * @param string $lang
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $lang)
    {
        $this->langService->delete($lang);
        
        $file = "en.json";
        return redirect()->route('admin.langs.index', compact('file'))->with("status", "Lang deleted successfully");
    }
}
