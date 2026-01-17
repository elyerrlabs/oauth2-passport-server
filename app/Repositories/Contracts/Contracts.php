<?php
namespace App\Repositories\Contracts;

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

interface Contracts
{
    /**
     * Search resources
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function search(Request $request);

    /**
     * Create new resource
     * @param array $data
     * @return void
     */
    public function create(array $data);

    /**
     * Search specific resource
     * @param string $id
     * @return void
     */
    public function find(string $id);

    /**
     * Update specific resource
     * @param string $id
     * @param array $data
     * @return void
     */
    public function update(string $id, array $data);

    /**
     * Delete specific resource
     * @param string $id 
     * @return void
     */
    public function delete(string $id);
}
