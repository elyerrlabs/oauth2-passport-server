#!/usr/bin/env php
<?php

declare(strict_types=1);

if ($argc !== 3) {
    fwrite(STDERR, "Usage: ./version <old-version> <new-version>\n");
    exit(1);
}

$oldVersion = $argv[1];
$newVersion = $argv[2];

$root = getcwd();

$ignoredDirectories = [
    '.git',
    'vendor',
    'node_modules',
    'storage',
    'bootstrap',
    'docs',
    'docker',
    'app',
    'config',
];

$ignoredFiles = [
    'composer.lock',
    'package.json',
    'package-lock.json',
];

$changed = 0;

$directory = new RecursiveDirectoryIterator(
    $root,
    FilesystemIterator::SKIP_DOTS
);

$filter = new RecursiveCallbackFilterIterator(
    $directory,
    function (SplFileInfo $current) use ($ignoredDirectories, $ignoredFiles) {

        if ($current->isDir()) {
            return !in_array(
                $current->getFilename(),
                $ignoredDirectories,
                true
            );
        }

        if ($current->isFile()) {
            return !in_array(
                $current->getFilename(),
                $ignoredFiles,
                true
            );
        }

        return true;
    }
);

$iterator = new RecursiveIteratorIterator($filter);

foreach ($iterator as $file) {

    /** @var SplFileInfo $file */

    if (!$file->isFile()) {
        continue;
    }

    $path = $file->getPathname();

    if (!is_readable($path) || !is_writable($path)) {
        continue;
    }

    $content = @file_get_contents($path);

    if ($content === false) {
        continue;
    }

    $original = $content;

    $filename = strtolower($file->getBasename());

    $isReadme = preg_match('/^changelog(\.[^.]+)?$/i', $filename);

    if ($isReadme) {

        $content = str_replace(
            'Unreleased',
            '[' . $newVersion . ']',
            $content
        );

    } else {

        $content = str_replace(
            $oldVersion,
            $newVersion,
            $content
        );
    }

    if ($content !== $original) {
        file_put_contents($path, $content);
        echo "Updated: {$path}\n";
        $changed++;
    }
}

if ($changed === 0) {
    echo "No files were modified.\n";
    exit(0);
}

echo PHP_EOL;
echo "Creating git commit..." . PHP_EOL;

exec('git add .', $out, $code);

if ($code !== 0) {
    fwrite(STDERR, "git add failed\n");
    exit(1);
}

$message = sprintf(
    'release: publish version %s',
    $newVersion
);

exec(
    'git commit -s -m ' . escapeshellarg($message),
    $out,
    $code
);

if ($code !== 0) {
    fwrite(STDERR, "git commit failed\n");
    exit(1);
}

echo "Done.\n";