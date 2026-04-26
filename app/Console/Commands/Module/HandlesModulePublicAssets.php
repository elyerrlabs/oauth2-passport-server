<?php

namespace App\Console\Commands\Module;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

trait HandlesModulePublicAssets
{
    protected function ensureModulePublicSymlink(string $modulePath): bool
    {
        $sourcePath = $modulePath . DIRECTORY_SEPARATOR . 'public';
        $linkPath = $this->modulePublicLinkPath($modulePath);
        $moduleName = basename($linkPath);
        $publicThirdPartyPath = dirname($linkPath);

        $this->info("Target: {$sourcePath}");
        $this->info("Link: {$linkPath}");

        if (!File::isDirectory($sourcePath)) {
            $this->warn("No public assets found for module [{$moduleName}].");
            return true;
        }

        if (!File::isDirectory($publicThirdPartyPath)) {
            $this->warn("Creating parent directory for link: {$publicThirdPartyPath}");
            File::makeDirectory($publicThirdPartyPath, 0755, true);
        }

        if (File::exists($linkPath) || is_link($linkPath)) {
            $this->warn("Deleting existing link or file at: {$linkPath}");
            File::delete($linkPath);
        }

        try {
            File::link($sourcePath, $linkPath);
            $this->info("Assets published for module {$moduleName}.");

            return true;
        } catch (\Throwable $e) {
            $this->error('Failed to create symlink!');
            $this->error('Error: ' . $e->getMessage());
            $this->error('Check that the parent directory exists and that the container user has permissions.');

            return false;
        }
    }

    protected function removeModulePublicSymlink(string $modulePath): void
    {
        $linkPath = $this->modulePublicLinkPath($modulePath);

        if (File::exists($linkPath) || is_link($linkPath)) {
            File::delete($linkPath);
            $this->warn("Removed public assets link: {$linkPath}");
        }
    }

    protected function modulePublicLinkPath(string $modulePath): string
    {
        return public_path('third-party/' . $this->modulePublicName($modulePath));
    }

    protected function modulePublicName(string $modulePath): string
    {
        $rawModuleName = basename($modulePath);

        return Str::of($rawModuleName)
            ->replace([',', '_'], ' ')
            ->snake()
            ->replace('_', '-')
            ->lower()
            ->toString();
    }
}
