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

trait Standard
{
    /**
     * Add standard to save notification in database
     * @param mixed $title
     * @param mixed $description
     * @param mixed $url
     * @return array{created_at: string, description: array|string|null, link: mixed, title: array|string|null}
     */
    public function notificationDatabase($title, $description, $url = null)
    {
        return [
            "title" => $title,
            "message" => $description,
            "link" => $url,
        ];
    }
}
