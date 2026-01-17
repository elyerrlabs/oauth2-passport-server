<?php
namespace App\Http\Controllers\Web\Admin\File;

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

use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Storage;
use App\Services\FileService;

class FileController extends WebController
{

    /**
     * Repositories
     * @var 
     */
    private $fileService;

    public function __construct(FileService $fileService)
    {
        parent::__construct();
        $this->fileService = $fileService;
    }

    /**
     * Delete files
     * @param string $id
     * @param string $owner_id
     * @return Illuminate\Http\JsonResponse|mixed
     */
    public function destroy(string $id, string $owner_id)
    {
        $model = $this->fileService->deleted($id, $owner_id);

        return $this->showOne($model);
    }


    public function show(string $id, string $owner_id)
    {
        $file = $this->fileService->findForUser($id, $owner_id);

        $path = Storage::disk($file->disk)->path($file->path);

        if (!file_exists($path))
            abort(404);

        return response()->make(
            file_get_contents($path),
            200,
            [
                'Content-Type' => $file->mime_type,
                'Content-Disposition' => 'inline'
            ]
        );
    }

}
