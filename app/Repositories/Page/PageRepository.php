<?php

namespace App\Repositories\Page;

use App\Models\Page\Page;

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

final class PageRepository
{
    /**
     * construct
     * @param Page $page
     */
    public function __construct(protected Page $page)
    {

    }

    /**
     * query
     * @return \Illuminate\Database\Eloquent\Builder<Page>
     */
    public function query()
    {
        return $this->page->query();
    }

    /**
     * find
     * @param string $id
     * @return object|Page|\stdClass|null
     */
    public function find(string $id)
    {
        return $this->query()->where('id', $id)->first();
    }

    /**
     * create
     * @param array $data
     * @return Page
     */
    public function create(array $data)
    {
        return $this->page->create($data);
    }

    /**
     * update
     * @param Page $page
     * @param array $data
     * @return bool|int
     */
    public function update(Page $page, array $data)
    {
        return $page->update($data);
    }
}
