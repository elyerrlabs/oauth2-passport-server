<?php

namespace App\Transformers\File;

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

use App\Models\Common\File;
use League\Fractal\TransformerAbstract;

class FilePrivateTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * Owner id
     * @var string
     */
    private $owner_id;

    /**
     * Owner ID
     * @param string $owner_id
     */
    public function __construct(string $owner_id)
    {
        $this->owner_id = $owner_id;
    }

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(File $file)
    {
        return [
            'id' => $file->id,
            'name' => $file->name,
            'original_name' => $file->original_name,
            'mime_type' => $file->mime_type,
            'extension' => $file->extension,
            'disk' => $file->disk,
            'path' => $file->path,
            'size' => $file->size,
            'visibility' => $file->visibility,
            'links' => [
                'show' => route('user.files.show', ['id' => $file->id, 'owner_id' => $this->owner_id]),
            ]
        ];
    }
}
