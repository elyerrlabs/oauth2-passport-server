<?php
namespace App\Http\Controllers\Web\Admin\File;
 
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

use App\Http\Controllers\WebController;
use App\Repositories\FileRepository;

class FileController extends WebController
{

    /**
     * Repositories
     * @var 
     */
    private $repository;

    public function __construct(FileRepository $fileRepository)
    {
        $this->repository = $fileRepository;
    }

    /**
     * Delete files
     * @param string $id
     * @param string $owner_id
     * @return Illuminate\Http\JsonResponse|mixed
     */
    public function destroy(string $id, string $owner_id)
    {
        $model = $this->repository->deleted($id, $owner_id);

        return $this->showOne($model);
    }
}
