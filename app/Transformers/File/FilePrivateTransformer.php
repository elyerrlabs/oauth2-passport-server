<?php

namespace App\Transformers\File;

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
