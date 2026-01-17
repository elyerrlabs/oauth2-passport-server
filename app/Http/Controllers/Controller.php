<?php

namespace App\Http\Controllers;

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

use Elyerr\ApiResponse\Assets\Asset;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Traits\Standard;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Core\User\Transformer\User\AuthTransformer;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests,
        Standard,
        DispatchesJobs,
        ValidatesRequests,
        JsonResponser,
        Asset;

    /**
     * Return information about the current users and transform date in the process
     * @return mixed
     */
    public function authenticated_user()
    {
        $user = fractal(Auth::user(), AuthTransformer::class);

        return json_decode(json_encode($user))->data;
    }

    /**
     * Transform model data
     * @param mixed $data
     * @param mixed $transformer
     */
    public function transform($data, $transformer)
    {
        return fractal(
            $data,
            $transformer
        )->toArray()['data'] ?? [];
    }

    /**
     * Transform collection
     * @param mixed $data
     * @param mixed $transformer
     * @return array
     */
    public function transformCollection($data, $transformer)
    {
        return fractal(
            $data,
            $transformer
        )->toArray() ?? [];
    }
}
