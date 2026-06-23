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
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, "<?php\n\nreturn [];\n");
    }

    /**
     * Resolve configuration file path.
     * @return string
     */
    private function loadConfigurationFile(): string
    {
        $paths = [
            base_path('env/settings.php'),
            base_path('settings.php'),
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

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

        return require $this->configFile;
    }

    /**
     *  Write settings file.
     * @param array $data
     * @return void
     */
    private function writeFile(array $data): void
    {
        $content = "<?php\n\nreturn " . var_export($data, true) . ";\n";

        file_put_contents($this->configFile, $content);
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
        if ($this->hasKey($key)) {
            return;
        }

        $data = $this->readFile();

        $data = $this->setNestedValue(
            $data,
            $key,
            $value
        );

        $this->writeFile($data);
    }

    /**
     * Add or replace key.
     */
    public function add(string $key, mixed $value): void
    {
        $data = $this->readFile();

        $data = $this->setNestedValue(
            $data,
            $key,
            $value
        );

        $this->writeFile($data);
    }

    /**
     * Delete a single key.
     */
    public function deleteKey(string $key): void
    {
        if (!$this->hasKey($key)) {
            throw new ReportError(
                "Key [$key] does not exist",
                404
            );
        }

        $data = $this->removeNestedValue(
            $this->readFile(),
            $key
        );

        $this->writeFile($data);
    }

    /**
     * Delete a module and all children.
     *
     * Example:
     * deleteKeysByModule('oauth')
     * deleteKeysByModule('oauth.client')
     */
    public function deleteKeysByModule(string $module): int
    {
        $data = $this->readFile();

        $flat = $this->flattenConfig($data);

        $deleted = 0;

        foreach (array_keys($flat) as $key) {

            if (
                $key === $module ||
                str_starts_with($key, $module . '.')
            ) {
                $data = $this->removeNestedValue(
                    $data,
                    $key
                );

                $deleted++;
            }
        }

        if ($deleted > 0) {
            $this->writeFile($data);
        }

        return $deleted;
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
     * Remove nested value using dot notation.
     */
    private function removeNestedValue(
        array $array,
        string $key
    ): array {
        $keys = explode('.', $key);

        $lastKey = array_pop($keys);

        $temp = &$array;

        foreach ($keys as $segment) {

            if (
                !isset($temp[$segment]) ||
                !is_array($temp[$segment])
            ) {
                return $array;
            }

            $temp = &$temp[$segment];
        }

        unset($temp[$lastKey]);

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


    /**
     * Import flat configuration.
     *
     * [
     *   'app.name' => 'Demo',
     *   'app.debug' => true,
     * ]
     */
    public function importOldConfig(): void
    {
        if (file_exists($this->configFile)) {
            unlink($this->configFile);
        }

        $this->loadConfigurationFile();

        $settings = \App\Models\Setting\Setting::pluck('value', 'key')->toArray();

        if (count($settings) == 0) {
            echo "Nothing to migrate\n";
            return;
        }

        $data = [];

        foreach ($settings as $key => $value) {
            $data = $this->setNestedValue(
                $data,
                $key,
                $value
            );
        }

        $this->writeFile($data);
    }
}
