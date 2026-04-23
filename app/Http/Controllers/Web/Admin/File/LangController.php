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
        $this->middleware('userCanAny:administrator:lang:full');
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
        $content = $request->input('content');

        try {
            if (str_ends_with($file, '.json')) {
                $this->validateJson($content);
            }

            if (str_ends_with($file, '.php')) {
                $this->validatePhp($content);
            }

            $this->langService->put($file, $content);

            return redirect()
                ->route('admin.langs.index', compact('file'))
                ->with("status", "Lang updated successfully");

        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'content' => $e->getMessage()
                ]);
        }
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

    protected function validateJson(string $content): void
    {
        json_decode($content);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Invalid JSON: ' . json_last_error_msg());
        }
    }

    protected function validatePhp(string $content): void
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'lang_') . '.php';

        file_put_contents($tmpFile, $content);

        exec("php -l " . escapeshellarg($tmpFile), $output, $result);

        unlink($tmpFile);

        if ($result !== 0) {
            throw new \RuntimeException('Invalid PHP syntax: ' . implode("\n", $output));
        }
    }
}
