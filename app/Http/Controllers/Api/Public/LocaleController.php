<?php


namespace App\Http\Controllers\Api\Public;
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

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocaleController extends Controller
{   
    /**
     * Retrieve the language
     * @param \Illuminate\Http\Request $request
     * @param string $locale
     * @return \Illuminate\Http\JsonResponse
     */
    public function locale(Request $request, string $locale = null)
    {
        $lang = $locale ?? substr($request->header('Accept-Language'), 0, 2);

        $path = base_path('lang') . '/' . $lang . '.json';

        if (!file_exists($path)) {
            $path = base_path('lang') . '/en.json';
        }

        $translations = json_decode(file_get_contents($path));

        return response()->json($translations);
    }
}
