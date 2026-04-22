<?php

namespace App\Services;

use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str; 

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

final class LangService
{
    protected string $path;

    public function __construct()
    {
        $this->path = base_path('lang');
    }

    /**
     * Get full lang tree
     */
    public function tree(): array
    {
        return $this->scan($this->path, '');
    }

    /**
     * Read file content by relative path
     */
    public function get(string $relativePath): string
    {
        $fullPath = $this->resolvePath($relativePath);

        if (!File::exists($fullPath)) {
            throw new ReportError("File not found: {$relativePath}", 404);
        }

        // JSON → return RAW content (no modification)
        if (Str::endsWith($fullPath, '.json')) {
            return File::get($fullPath);
        }

        // PHP → convert array to JSON (for editor)
        if (Str::endsWith($fullPath, '.php')) {
            return file_get_contents($fullPath);
        }

        // fallback → raw content
        return File::get($fullPath);
    }

    /**
     * Save content to file
     * @param string $relativePath
     * @param string|array $content 
     * @return void
     */
    public function put(string $relativePath, string|array $content): void
    {
        $fullPath = $this->resolvePath($relativePath);

        File::put($fullPath, $content);
    }

    /**
     * Scan directories recursively
     * @param string $fullPath
     * @param string $relativePath
     * @return array
     */
    protected function scan(string $fullPath, string $relativePath): array
    {
        $items = [];

        // Folders
        foreach (File::directories($fullPath) as $dir) {

            $folder = basename($dir);

            if ($folder === 'vendor') {
                continue;
            }

            $path = ltrim($relativePath . '/' . $folder, '/');

            $items[] = [
                'label' => $folder,
                'type' => 'folder',
                'path' => $path,
                'children' => $this->scan($dir, $path),
            ];
        }

        // Files
        foreach (File::files($fullPath) as $file) {

            $name = $file->getFilename();
            $path = ltrim($relativePath . '/' . $name, '/');

            $items[] = [
                'label' => $name,
                'type' => 'file',
                'path' => $path,
            ];
        }

        return $this->sort($items);
    }

    
    /**
     * Ensure safe path (prevent path traversal)
     * @param string $relativePath
     * @throws ReportError
     * @return string
     */
    protected function resolvePath(string $relativePath): string
    {
        $relativePath = ltrim($relativePath, '/');

        $fullPath = realpath($this->path . '/' . $relativePath);

        if (!$fullPath || !Str::startsWith($fullPath, realpath($this->path))) {
            throw new ReportError("Invalid path", 403);
        }

        return $fullPath;
    }

    /**
     * Sort folders first, then files
     * @param array $items
     * @return array
     */
    protected function sort(array $items): array
    {
        usort($items, function ($a, $b) {
            if ($a['type'] === $b['type']) {
                return strcmp($a['label'], $b['label']);
            }

            return $a['type'] === 'folder' ? -1 : 1;
        });

        return $items;
    }

    /**
     * Create new locale
     * @param string $locale
     * @throws ReportError
     * @return void
     */
    public function createLocale(string $locale): void
    {
        $locale = strtolower($locale);

        $source = $this->path . '/en';
        $target = $this->path . '/' . $locale;

        $sourceJson = $this->path . '/en.json';
        $targetJson = $this->path . '/' . $locale . '.json';

        // Validate
        if (!File::exists($source)) {
            throw new ReportError('Base locale [en] not found', 404);
        }

        if (File::exists($target) || File::exists($targetJson)) {
            throw new ReportError("Locale [{$locale}] already exists", 403);
        }

        // 1. Create directory
        File::makeDirectory($target, 0755, true);

        // 2. Copy PHP files recursively
        $this->copyDirectory($source, $target);

        // 3. Create JSON file
        if (File::exists($sourceJson)) {
            File::copy($sourceJson, $targetJson);
        } else {
            File::put($targetJson, json_encode([], JSON_PRETTY_PRINT));
        }
    }


    /**
     * Copy directory
     * @param string $from
     * @param string $to
     * @return void
     */
    protected function copyDirectory(string $from, string $to): void
    {
        foreach (File::allFiles($from) as $file) {

            $relativePath = Str::after($file->getPathname(), $from . DIRECTORY_SEPARATOR);
            $targetPath = $to . DIRECTORY_SEPARATOR . $relativePath;

            File::ensureDirectoryExists(dirname($targetPath));

            File::copy($file->getPathname(), $targetPath);
        }
    }

    /**
     * Delete
     * @param string $file
     * @throws ReportError
     * @return void
     */
    public function delete(string $file): void
    {
        // Extract only name
        $locale = Str::before($file, '.');

        // DO NOT REMOVE es and en
        if (in_array($locale, ['en', 'es'])) {
            throw new ReportError("Cannot delete base language: {$locale}", 403);
        }

        $basePath = base_path('lang');

        $jsonPath = $basePath . DIRECTORY_SEPARATOR . "{$locale}.json";
        $dirPath = $basePath . DIRECTORY_SEPARATOR . $locale;

        // Delete json file
        if (File::exists($jsonPath)) {
            File::delete($jsonPath);
        }

        // Delete directory
        if (File::exists($dirPath) && File::isDirectory($dirPath)) {
            File::deleteDirectory($dirPath);
        }

        // Check delete file and directory
        if (!File::exists($jsonPath) && !File::exists($dirPath)) {
            return;
        }
    }
}