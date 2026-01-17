<?php

namespace App\Services;

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

use App\Repositories\FileRepository;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Support\Facades\Storage;

class FileService
{

    /**
     * File repository
     * @var FileRepository
     */
    private $fileRepository;


    /**
     * Construct
     * @param FileRepository $fileRepository
     */
    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    /**
     * Find file
     * @param string $id
     * @return \App\Models\Common\File|null
     */
    public function find(string $id)
    {
        return $this->fileRepository->find($id);
    }

    /**
     * Search file for user
     * @param string $id
     * @param string $owner_id
     * @return \App\Models\Common\File|null
     */
    public function findForUser(string $id, string $owner_id)
    {
        $model = $this->fileRepository->find($id);

        throw_if(
            empty($model),
            new ReportError(__('File does not exists'), 404)
        );

        throw_if(
            $model->fileable_id != $owner_id,
            new ReportError(__('File does not exists'), 404)
        );

        return $model;
    }

    /**
     * Delete file
     * @param string $id
     * @param string $owner_id
     */
    public function deleted(string $id, string $owner_id)
    {
        $model = $this->fileRepository->find($id);

        throw_if(
            empty($model),
            new ReportError(__('File does not exists'), 404)
        );

        throw_if(
            $model->fileable_id != $owner_id,
            new ReportError(__('File does not exists'), 404)
        );

        if (!empty($model)) {
            Storage::delete($model->path);

            $model->delete();
        }

        return $model;
    }

    /**
     * Process Image
     * @param string $id
     * @param array $images images
     * @return array
     */
    public function processImage(mixed $images = [], string $disk = 'tmp')
    {
        if (empty($images)) {
            return [];
        }

        $paths = [];

        foreach ($images as $key => $file) {

            if ($file instanceof \Illuminate\Http\UploadedFile) {

                $path = $file->store('', 'tmp');

                $paths[$key] = $path;
            }
        }

        return $paths;
    }

    /**
     * Save image
     * @param mixed $model
     * @param array $images
     * @param string $dirpath
     * @param string $disk
     * @param string $visibility
     * @throws ReportError
     * @return void
     */
    public function saveImage(mixed $model, array $images, string $dirpath, string $disk = 'public', string $visibility = 'private')
    {
        if (empty($images)) {
            return;
        }

        if (empty($model)) {
            throw new ReportError(__(
                'Product not found with :id. Image processing has been cancelled.',
                ['id' => $model->id]
            ), 404);
        }

        foreach ($images as $path) {
            // Example path -> "temp/xxxx.jpg"
            if (!Storage::disk('tmp')->exists($path)) {
                continue;
            }

            // From (relative path)
            $from = $path;

            // Create new path
            $filename = basename($path);
            $newPath = $dirpath . "/" . $filename;

            // Move file (relative → relative)
            Storage::disk($disk)->put(
                $newPath,
                Storage::disk('tmp')->get($from)
            );

            Storage::disk('tmp')->delete($from);

            // Absolute path to read metadata
            $absolute = Storage::disk($disk)->path($newPath);

            // Metadata
            $extension = pathinfo($absolute, PATHINFO_EXTENSION);
            $mime = mime_content_type($absolute);
            $size = filesize($absolute);

            // Save DB record
            $model->files()->create([
                'name' => pathinfo($absolute, PATHINFO_FILENAME),
                'original_name' => $filename,
                'mime_type' => $mime,
                'extension' => $extension,
                'disk' => $disk,
                'size' => $size,
                'visibility' => $visibility,
                'path' => $newPath,
            ]);
        }
    }
}
