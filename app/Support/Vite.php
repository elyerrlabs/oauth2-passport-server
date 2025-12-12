<?php

namespace App\Support;

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

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Js;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;

final class Vite extends \Illuminate\Foundation\Vite
{
    /**
     * Generate Vite tags for an entrypoint.
     *
     * @param  string|string[]  $entrypoints
     * @param  string|null  $buildDirectory
     * @return \Illuminate\Support\HtmlString
     *
     * @throws \Exception
     */
    public function __invoke($entrypoints, $buildDirectory = null)
    {
        $currentRoute = request()->route();

        // Verify the module path exists
        if (isset($currentRoute->action['module_path'])) {
            $path = $currentRoute->action['module_path'];

            $entrypoints = array_map(function ($item) use ($path) {

                // Absolute path
                $fullPath = base_path("$path/$item");

                // Verify if the path exists so set new path
                if (file_exists($fullPath)) {

                    return "$path/$item";
                }
                // default path
                return $item;

            }, $entrypoints);
        }

        $entrypoints = new Collection($entrypoints);
        $buildDirectory ??= $this->buildDirectory;

        if ($this->isRunningHot()) {
            return new HtmlString(
                $entrypoints
                    ->prepend('@vite/client')
                    ->map(fn($entrypoint) => $this->makeTagForChunk($entrypoint, $this->hotAsset($entrypoint), null, null))
                    ->join('')
            );
        }

        $manifest = $this->manifest($buildDirectory);

        $tags = new Collection;
        $preloads = new Collection;

        foreach ($entrypoints as $entrypoint) {
            $chunk = $this->chunk($manifest, $entrypoint);

            $preloads->push([
                $chunk['src'],
                $this->assetPath("{$buildDirectory}/{$chunk['file']}"),
                $chunk,
                $manifest,
            ]);

            foreach ($chunk['imports'] ?? [] as $import) {
                $preloads->push([
                    $import,
                    $this->assetPath("{$buildDirectory}/{$manifest[$import]['file']}"),
                    $manifest[$import],
                    $manifest,
                ]);

                foreach ($manifest[$import]['css'] ?? [] as $css) {
                    $partialManifest = (new Collection($manifest))->where('file', $css);

                    $preloads->push([
                        $partialManifest->keys()->first(),
                        $this->assetPath("{$buildDirectory}/{$css}"),
                        $partialManifest->first(),
                        $manifest,
                    ]);

                    $tags->push($this->makeTagForChunk(
                        $partialManifest->keys()->first(),
                        $this->assetPath("{$buildDirectory}/{$css}"),
                        $partialManifest->first(),
                        $manifest
                    ));
                }
            }

            $tags->push($this->makeTagForChunk(
                $entrypoint,
                $this->assetPath("{$buildDirectory}/{$chunk['file']}"),
                $chunk,
                $manifest
            ));

            foreach ($chunk['css'] ?? [] as $css) {
                $partialManifest = (new Collection($manifest))->where('file', $css);

                $preloads->push([
                    $partialManifest->keys()->first(),
                    $this->assetPath("{$buildDirectory}/{$css}"),
                    $partialManifest->first(),
                    $manifest,
                ]);

                $tags->push($this->makeTagForChunk(
                    $partialManifest->keys()->first(),
                    $this->assetPath("{$buildDirectory}/{$css}"),
                    $partialManifest->first(),
                    $manifest
                ));
            }
        }

        [$stylesheets, $scripts] = $tags->unique()->partition(fn($tag) => str_starts_with($tag, '<link'));

        $preloads = $preloads->unique()
            ->sortByDesc(fn($args) => $this->isCssPath($args[1]))
            ->map(fn($args) => $this->makePreloadTagForChunk(...$args));

        $base = $preloads->join('') . $stylesheets->join('') . $scripts->join('');

        if ($this->prefetchStrategy === null || $this->isRunningHot()) {
            return new HtmlString($base);
        }

        $discoveredImports = [];

        return (new Collection($entrypoints))
            ->flatMap(fn($entrypoint) => (new Collection($manifest[$entrypoint]['dynamicImports'] ?? []))
                ->map(fn($import) => $manifest[$import])
                ->filter(fn($chunk) => str_ends_with($chunk['file'], '.js') || str_ends_with($chunk['file'], '.css'))
                ->flatMap($f = function ($chunk) use (&$f, $manifest, &$discoveredImports) {
                    return (new Collection([...$chunk['imports'] ?? [], ...$chunk['dynamicImports'] ?? []]))
                        ->reject(function ($import) use (&$discoveredImports) {
                            if (isset($discoveredImports[$import])) {
                                return true;
                            }

                            return !$discoveredImports[$import] = true;
                        })
                        ->reduce(
                            fn($chunks, $import) => $chunks->merge(
                                $f($manifest[$import])
                            ),
                            new Collection([$chunk])
                        )
                        ->merge((new Collection($chunk['css'] ?? []))->map(
                            fn($css) => (new Collection($manifest))->first(fn($chunk) => $chunk['file'] === $css) ?? [
                                'file' => $css,
                            ],
                        ));
                })
                ->map(function ($chunk) use ($buildDirectory, $manifest) {
                    return (new Collection([
                        ...$this->resolvePreloadTagAttributes(
                            $chunk['src'] ?? null,
                            $url = $this->assetPath("{$buildDirectory}/{$chunk['file']}"),
                            $chunk,
                            $manifest,
                        ),
                        'rel' => 'prefetch',
                        'fetchpriority' => 'low',
                        'href' => $url,
                    ]))->reject(
                            fn($value) => in_array($value, [null, false], true)
                        )->mapWithKeys(fn($value, $key) => [
                            $key = (is_int($key) ? $value : $key) => $value === true ? $key : $value,
                        ])->all();
                })
                ->reject(fn($attributes) => isset($this->preloadedAssets[$attributes['href']])))
            ->unique('href')
            ->values()
            ->pipe(fn($assets) => with(Js::from($assets), fn($assets) => match ($this->prefetchStrategy) {
                'waterfall' => new HtmlString($base . <<<HTML

                    <script{$this->nonceAttribute()}>
                         window.addEventListener('{$this->prefetchEvent}', () => window.setTimeout(() => {
                            const makeLink = (asset) => {
                                const link = document.createElement('link')

                                Object.keys(asset).forEach((attribute) => {
                                    link.setAttribute(attribute, asset[attribute])
                                })

                                return link
                            }

                            const loadNext = (assets, count) => window.setTimeout(() => {
                                if (count > assets.length) {
                                    count = assets.length

                                    if (count === 0) {
                                        return
                                    }
                                }

                                const fragment = new DocumentFragment

                                while (count > 0) {
                                    const link = makeLink(assets.shift())
                                    fragment.append(link)
                                    count--

                                    if (assets.length) {
                                        link.onload = () => loadNext(assets, 1)
                                        link.onerror = () => loadNext(assets, 1)
                                    }
                                }

                                document.head.append(fragment)
                            })

                            loadNext({$assets}, {$this->prefetchConcurrently})
                        }))
                    </script>
                    HTML),
                'aggressive' => new HtmlString($base . <<<HTML

                    <script{$this->nonceAttribute()}>
                         window.addEventListener('{$this->prefetchEvent}', () => window.setTimeout(() => {
                            const makeLink = (asset) => {
                                const link = document.createElement('link')

                                Object.keys(asset).forEach((attribute) => {
                                    link.setAttribute(attribute, asset[attribute])
                                })

                                return link
                            }

                            const fragment = new DocumentFragment;
                            {$assets}.forEach((asset) => fragment.append(makeLink(asset)))
                            document.head.append(fragment)
                         }))
                    </script>
                    HTML),
            }));
    }
}
