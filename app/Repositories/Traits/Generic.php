<?php

namespace App\Repositories\Traits;

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

use Core\User\Model\Scope;


trait Generic
{
    /**
     * Check if it the services is duplicated
     * @param mixed $value
     * @return array
     */
    public function checkServices($value)
    {
        $services = [];

        foreach ($value as $key) {
            $scope = Scope::with(['service'])->find($key);
            array_push($services, $scope->service->slug);
        }

        $count = array_count_values($services);

        $duplicated = array_keys(array_filter($count, function ($amount) {
            return $amount > 1;
        }));

        return $duplicated;
    }
}

