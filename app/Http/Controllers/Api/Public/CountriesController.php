<?php
namespace App\Http\Controllers\Api\Public;

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
