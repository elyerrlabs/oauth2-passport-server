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

use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Sitemap as SitemapTag;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

class SiteMapService
{
    /**
     * Directory
     * @var string
     */
    private $directory;

    /**
     * Directory
     * @var string
     */
    private $indexPath;

    /**
     * Robot
     * @var string
     */
    private $robot;


    /**
     * Uri
     * @var string
     */
    private $uri;


    public function __construct()
    {
        $sitemapName = 'sitemap.xml';
        $this->uri = config('app.url') . "/" . $sitemapName;
        $this->directory = public_path('sitemaps');
        $this->indexPath = public_path($sitemapName);
        $this->robot = public_path('robots.txt');

        // Create sitemap directory
        if (!is_dir($this->directory)) {
            mkdir($this->directory, 0777, true);
        }
    }

    /**
     * Get full path of a sitemap file
     */
    public function getSitemap(string $filename): string
    {
        return $this->directory . '/' . $filename . '.xml';
    }


    public function updateRobot()
    {
        // Init  robots.txt 
        if (!file_exists($this->robot)) {
            file_put_contents($this->robot, "User-agent: *\nDisallow:\n");
        }

        $robotsContent = file_get_contents($this->robot);

        // Delete Sitemap line
        $robotsContent = preg_replace('/^Sitemap:.*$/m', '', $robotsContent);

        // Update line
        $robotsContent = trim($robotsContent) . "\nSitemap: $this->uri\n";

        file_put_contents($this->robot, $robotsContent);
    }

    /**
     * List routes and mark registered ones
     */
    public function listRoutes()
    {
        $registeredUrls = $this->getAllRegisteredUrls();

        return collect(Route::getRoutes())
            ->filter(fn($route) => in_array('GET', $route->methods()))
            ->map(function ($route) use ($registeredUrls) {

                $base = rtrim(config('app.url'), '/');
                $uri = ltrim($route->uri(), '/');
                $url = $base . '/' . $uri;

                return [
                    'url' => $url,
                    'name' => $route->getName(),
                    'image' => null,
                    'registered' => in_array($url, $registeredUrls),
                    'links' => [
                        'delete' =>
                            in_array($url, $registeredUrls)
                            ? route('admin.sitemaps.delete', ['url' => base64_encode($url)])
                            : null,
                    ],
                ];
            })
            ->values();
    }

    /**
     * Register entry into a specific sitemap file
     */
    public function register(
        string $file,
        string $url,
        ?string $image = null,
        ?string $changefreq = 'weekly',
        ?float $priority = 0.5
    ): bool {

        $this->updateRobot();

        $path = $this->getSitemap($file);

        // Load empty sitemap
        $sitemap = Sitemap::create();

        // Read exists routes
        if (file_exists($path)) {
            $xml = simplexml_load_file($path);

            foreach ($xml->url as $node) {
                $loc = (string) $node->loc;

                $entry = Url::create($loc);

                // lastmod
                if (isset($node->lastmod)) {
                    $entry->setLastModificationDate(Carbon::parse((string) $node->lastmod));
                }

                // changefreq
                if (isset($node->changefreq)) {
                    $entry->setChangeFrequency((string) $node->changefreq);
                }

                // priority
                if (isset($node->priority)) {
                    $entry->setPriority((float) $node->priority);
                }

                // images
                if (isset($node->{'image:image'})) {
                    foreach ($node->{'image:image'} as $imgNode) {
                        $img = (string) $imgNode->{'image:loc'};
                        $entry->addImage($img);
                    }
                }

                // Add reconstructed sitemap
                $sitemap->add($entry);
            }
        }

        // Verify duplicated data
        foreach ($sitemap->getTags() as $tag) {
            if ($tag instanceof Url && $tag->url === $url) {
                return false;
            }
        }

        // Create new metadata
        $new = Url::create($url)
            ->setLastModificationDate(now())
            ->setChangeFrequency($changefreq)
            ->setPriority($priority);

        if ($image) {
            $new->addImage($image);
        }

        // Add new entry
        $sitemap->add($new);

        // Save individual site map
        $sitemap->writeToFile($path);

        // Update index
        $this->updateIndex();

        return true;
    }


    /**
     * Create/update sitemap index
     */
    public function updateIndex(): void
    {
        $index = SitemapIndex::create();

        foreach (glob($this->directory . '/*.xml') as $file) {
            $fileName = basename($file);

            $index->add(
                SitemapTag::create(url('sitemaps/' . $fileName))
                    ->setLastModificationDate(now())
            );
        }

        $index->writeToFile($this->indexPath);
    }

    /**
     * Get URLs of a specific sitemap file
     */
    public function getUrls(string $file): array
    {
        $path = $this->getSitemap($file);

        if (!file_exists($path)) {
            return [];
        }

        $xml = simplexml_load_file($path);
        $list = [];

        foreach ($xml->url as $node) {
            $list[] = (string) $node->loc;
        }

        return $list;
    }

    /**
     * Get ALL URLs from ALL sitemap files
     */
    public function getAllRegisteredUrls(): array
    {
        $all = [];

        foreach (glob($this->directory . '/*.xml') as $file) {
            $xml = simplexml_load_file($file);

            foreach ($xml->url as $node) {
                $all[] = (string) $node->loc;
            }
        }

        return $all;
    }

    /**
     * Remove a specific URL from all sitemaps
     */
    public function remove(string $encodedUrl): bool
    {
        $url = base64_decode($encodedUrl);

        foreach (glob($this->directory . '/*.xml') as $file) {
            $xml = simplexml_load_file($file);
            $new = Sitemap::create();
            $modified = false;

            foreach ($xml->url as $node) {
                $loc = (string) $node->loc;

                if ($loc !== $url) {
                    $new->add(Url::create($loc));
                } else {
                    $modified = true;
                }
            }

            if ($modified) {
                $new->writeToFile($file);
            }
        }

        // Rebuild index
        $this->updateIndex();

        return true;
    }

    /**
     * Reset sitemaps
     * @return bool
     */
    public function reset()
    {
        // Delete index file
        if (file_exists($this->indexPath)) {
            unlink($this->indexPath);
        }

        // Delete all sitemap files inside the directory
        if (is_dir($this->directory)) {
            $files = glob($this->directory . '/*.xml');

            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }

        // Reset robots.txt to block indexing
        unlink($this->robot);
        $robotsContent = "User-agent: *\nDisallow: /";
        file_put_contents($this->robot, $robotsContent);

        return true;
    }

}
