<?php
namespace App\Models\Setting;

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

use App\Models\Master;
use App\Transformers\Session\SessionTransformer;
use Elyerr\ApiResponse\Assets\Asset;

class Session extends Master
{
    use Asset;

    public $table = "sessions";

    public $transformer = SessionTransformer::class;

    protected $fillable = [
        //
    ];

    protected $hidden = [
        //  'user_id'
    ];

    public function getLastActivityAttribute($value)
    {
        $date = date('Y-m-d H:i:s', $value);

        return $this->format_date($date);
    }
}
