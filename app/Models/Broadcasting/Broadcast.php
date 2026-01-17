<?php

namespace App\Models\Broadcasting;

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
use App\Transformers\Broadcast\BroadcastTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\QueryException;

class Broadcast extends Master
{
    use HasFactory;

    /**
     * Table name
     * @var string
     */
    public $table = "broadcasts";

    /**
     * Transformer class
     * @var 
     */
    public $transformer = BroadcastTransformer::class;

    /**
     * Fillable properties
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'slug',
        'system'
    ];

    /**
     * Cast properties
     * @var array
     */
    public $casts = [
        'system' => 'boolean',
    ];

    /**
     * Retrieve default channels for the system
     * @return mixed
     */
    public static function channelsByDefault()
    {
        return json_decode(file_get_contents(base_path('database/extra/channels.json')));
    }
}
