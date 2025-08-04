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

use Illuminate\Http\Request;
use App\Models\Global\Country;
use App\Http\Controllers\Controller;

class CountriesController extends Controller
{
    /**
     * Gateway to get the all countries
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Global\Country $country
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request, Country $country)
    {
        $countries = $country->query();

        if ($request->name) {
            $countries = $countries->whereRaw("LOWER(name_en) LIKE ?", ['%' . strtolower($request->name) . '%']);
        }

        $countries = $this->orderByBuilder($countries);

        return $this->showAllByBuilder($countries, null, 200, false);
    }
}
