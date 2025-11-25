<?php

namespace App\Services;

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

use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Support\Facades\Storage;

class FileService
{
    /**
     * Process Image
     * @param string $id
     * @param array $images images
     * @return array
     */
    public function processImage(mixed $images = [])
    {
        if (empty($images)) {
            return [];
        }
        Storage::disk('local')->makeDirectory('temp');

        $paths = [];


        foreach ($images as $key => $file) {

            if ($file instanceof \Illuminate\Http\UploadedFile) {

                $path = $file->store('temp', 'local');

                $paths[$key] = $path;
            }
        }

        return $paths;
    }

    /**
     * Save image
     * @param mixed $model object of model
     * @param array $data image url
     * @param string $dirpath  path to save images
     * @throws ReportError
     * @return void
     */
    public function saveImage(mixed $model, array $images, string $dirpath)
    {
        if (empty($images)) {
            return;
        }

        // Create destination directory
        Storage::disk('public')->makeDirectory($dirpath);

        if (empty($model)) {
            throw new ReportError(__(
                'Product not found with :id. Image processing has been cancelled.',
                ['id' => $model->id]
            ), 404);
        }

        foreach ($images as $path) {

            // Example path -> "temp/xxxx.jpg"
            if (!Storage::disk('local')->exists($path)) {
                continue;
            }

            // From (relative path)
            $from = $path;

            // Create new path
            $filename = basename($path);
            $newPath = $dirpath . "/" . $filename;

            // Move file (relative → relative)
            Storage::disk('public')->put(
                $newPath,
                Storage::disk('local')->get($from)
            );
            Storage::disk('local')->delete($from);

            // Absolute path to read metadata
            $absolute = Storage::disk('public')->path($newPath);

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
                'disk' => 'public',
                'size' => $size,
                'visibility' => 'public',
                'path' => $newPath,
            ]);
        }
    }
}
