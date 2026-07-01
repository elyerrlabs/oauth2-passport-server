<?php

namespace App\Repositories;

use Elyerr\ApiResponse\Exceptions\ReportError;

class SettingRepository
{
    /**
     * Configuration file path.
     */
    protected string $configFile;

    public function __construct()
    {
        $this->configFile = $this->loadConfigurationFile();
    }

    /**
     * Create settings file if it does not exist.
     * @param string $path
     * @return void
     */
    private function createSettingsFile(string $path): void
    {
        if (file_exists($path)) {
            return;
        }

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0750, true);
        }

        $this->writeFileAtPath($path, []);
    }

    /**
     * Resolve configuration file path.
     * @return string
     */
    private function loadConfigurationFile(): string
    {
        $file = app()->environment('production', 'staging')
            ? base_path('env/settings.php')
            : base_path('settings.php');

        $this->createSettingsFile($file);

        return $file;
    }

    /**
     * Read settings file.
     * @throws ReportError
     * @return array
     */
    private function readFile(): array
    {
        if (!file_exists($this->configFile)) {
            throw new ReportError('Settings file not found', 500);
        }

        if (function_exists('opcache_invalidate')) {
            opcache_invalidate($this->configFile, true);
        }

        $data = require $this->configFile;

        return is_array($data) ? $data : [];
    }

    /**
     *  Write settings file.
     * @param array $data
     * @return void
     */
    private function writeFile(array $data): void
    {
        $this->writeFileAtPath($this->configFile, $data);
    }

    /**
     * Read, mutate and write settings while holding the same file lock.
     * @param callable $callback
     * @return mixed
     */
    private function updateFile(callable $callback): mixed
    {
        return $this->withFileLock($this->configFile, function () use ($callback) {
            $data = $this->readFile();
            $result = $callback($data);

            if (is_array($result)) {
                $data = $result;
                $result = null;
            }

            $this->writeFileUnlocked($this->configFile, $data);

            return $result;
        });
    }

    /**
     * Write a PHP config array using a lock and atomic rename.
     * @param string $path
     * @param array $data
     * @return void
     */
    private function writeFileAtPath(string $path, array $data): void
    {
        $this->withFileLock($path, function () use ($path, $data) {
            $this->writeFileUnlocked($path, $data);
        });
    }

    /**
     * Run a callback with an exclusive lock for a config file.
     * @param string $path
     * @param callable $callback
     * @return mixed
     */
    private function withFileLock(string $path, callable $callback): mixed
    {
        $directory = dirname($path);

        if (!is_dir($directory)) {
            mkdir($directory, 0750, true);
        }

        $lockPath = "{$path}.lock";
        $lock = fopen($lockPath, 'c');

        if ($lock === false) {
            throw new ReportError('Unable to open settings lock file', 500);
        }

        try {
            if (!flock($lock, LOCK_EX)) {
                throw new ReportError('Unable to lock settings file', 500);
            }

            return $callback();
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }

    /**
     * Write a PHP config array. Caller must hold the file lock.
     * @param string $path
     * @param array $data
     * @return void
     */
    private function writeFileUnlocked(string $path, array $data): void
    {
        $content = "<?php\n\nreturn " . var_export($data, true) . ";\n";
        $directory = dirname($path);
        $temporary = tempnam($directory, basename($path) . '.tmp.');

        if ($temporary === false) {
            throw new ReportError('Unable to create temporary settings file', 500);
        }

        if (file_put_contents($temporary, $content, LOCK_EX) === false) {
            @unlink($temporary);
            throw new ReportError('Unable to write temporary settings file', 500);
        }

        chmod($temporary, 0640);

        if (!rename($temporary, $path)) {
            @unlink($temporary);
            throw new ReportError('Unable to replace settings file', 500);
        }

        if (function_exists('opcache_invalidate')) {
            opcache_invalidate($path, true);
        }
    }

    /**
     * Get all configuration flattened.
     *
     * [
     *   'app.name' => 'Demo',
     *   'app.debug' => true,
     * ]
     */
    public function getConfig(): array
    {
        return $this->flattenConfig($this->readFile());
    }

    /**
     * Get single key.
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getKey(string $key, mixed $default = null): mixed
    {
        return $this->getNestedValue(
            $this->readFile(),
            $key,
            $default
        );
    }

    /**
     * Check if key exists.
     * @param string $key
     * @return bool
     */
    public function hasKey(string $key): bool
    {
        return $this->getNestedValue(
            $this->readFile(),
            $key,
            '__missing__'
        ) !== '__missing__';
    }

    /**
     * Add key only if it does not exist.
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function load(string $key, mixed $value): void
    {
        $this->updateFile(function (array $data) use ($key, $value) {
            if ($this->getNestedValue($data, $key, '__missing__') !== '__missing__') {
                return $data;
            }

            return $this->setNestedValue($data, $key, $value);
        });
    }

    /**
     * Add or replace key.
     */
    public function add(string $key, mixed $value): void
    {
        $this->updateFile(function (array $data) use ($key, $value) {
            return $this->setNestedValue($data, $key, $value);
        });
    }

    /**
     * Add or replace several keys with a single read/write cycle.
     * @param array<string, mixed> $values
     * @return void
     */
    public function addMany(array $values): void
    {
        $this->updateFile(function (array $data) use ($values) {
            foreach ($values as $key => $value) {
                $data = $this->setNestedValue($data, $key, $value);
            }

            return $data;
        });
    }

    /**
     * Delete a module and all children.
     *
     * Examples:
     * deleteKeys('oauth')
     * deleteKeys('oauth.client')
     */
    public function deleteKeys(string $module): int
    {
        $deleted = 0;

        $this->updateFile(function (array $data) use ($module, &$deleted) {

            $flat = $this->flattenConfig($data);

            foreach ($flat as $key => $value) {

                if (str_contains($key, $module)) {
                    unset($flat[$key]);
                    $deleted++;
                }
            }

            $data = $this->nestedValues($flat);

            return $data;
        });

        return $deleted;
    }


    /**
     * Convert flat dot notation array to nested array.
     */
    protected function nestedValues(array $flat): array
    {
        $nested = [];

        foreach ($flat as $key => $value) {

            $segments = explode('.', $key);

            $current = &$nested;

            foreach ($segments as $segment) {

                if (!isset($current[$segment]) || !is_array($current[$segment])) {
                    $current[$segment] = [];
                }

                $current = &$current[$segment];
            }

            $current = $value;

            unset($current);
        }

        return $nested;
    }

    /**
     * Get nested value from dot notation.
     */
    private function getNestedValue(
        array $array,
        string $key,
        mixed $default = null
    ): mixed {
        foreach (explode('.', $key) as $segment) {

            if (
                !is_array($array) ||
                !array_key_exists($segment, $array)
            ) {
                return $default;
            }

            $array = $array[$segment];
        }

        return $array;
    }

    /**
     * Set nested value using dot notation.
     */
    private function setNestedValue(
        array $array,
        string $key,
        mixed $value
    ): array {
        $temp = &$array;

        foreach (explode('.', $key) as $segment) {

            if (
                !isset($temp[$segment]) ||
                !is_array($temp[$segment])
            ) {
                $temp[$segment] = [];
            }

            $temp = &$temp[$segment];
        }

        $temp = $value;

        return $array;
    }

    /**
     * Flatten nested configuration.
     */
    private function flattenConfig(
        array $array,
        string $prefix = ''
    ): array {
        $result = [];

        foreach ($array as $key => $value) {

            $newKey = $prefix === ''
                ? $key
                : $prefix . '.' . $key;

            if (is_array($value)) {
                $result += $this->flattenConfig(
                    $value,
                    $newKey
                );
            } else {
                $result[$newKey] = $value;
            }
        }

        return $result;
    }
}
