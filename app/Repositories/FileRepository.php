<?php

namespace App\Repositories;

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
use Illuminate\Support\Facades\Storage;

class FileRepository
{

    private $model;

    public function __construct(File $file)
    {
        $this->model = $file;
    }

    /**
     * Find resource
     * @param string $id
     * @return File|null
     */
    public function find(string $id): File|null
    {
        return $this->model->find($id);
    }

    /**
     * Delete file
     * @param string $id
     * @param string $owner_id
     * @return File|null
     */
    public function deleted(string $id, string $owner_id)
    {
        $model = $this->find($id);

        throw_if(empty($model), __('File does not exists'));

        throw_if($model->fileable_id != $owner_id, __('File does not exists'));

        if (!empty($model)) {
            Storage::delete($model->path);

            $model->delete();
        }

        return $model;
    }
}
