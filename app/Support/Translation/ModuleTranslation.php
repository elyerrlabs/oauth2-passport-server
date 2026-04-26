<?php

namespace App\Support\Translation;

use Illuminate\Support\Facades\File;

class ModuleTranslation
{
    /**
     * Build the translation loader paths.
     *
     * Host lang path stays last so local files can override module values.
     *
     * @return array<int, string>
     */
    public static function loaderPaths(): array
    {
        return array_values(array_filter(array_unique(array_merge(
            [base_path('vendor/laravel/framework/src/Illuminate/Translation/lang')],
            static::moduleLangPaths(),
            [lang_path()]
        ))));
    }

    /**
     * Discover module translation directories.
     *
     * @return array<int, string>
     */
    public static function moduleLangPaths(): array
    {
        $paths = [];

        foreach (['core', 'third-party'] as $root) {
            $base = base_path($root);

            if (!is_dir($base)) {
                continue;
            }

            foreach (File::directories($base) as $modulePath) {
                $langPath = $modulePath . '/lang';

                if (is_dir($langPath)) {
                    $paths[] = $langPath;
                }
            }
        }

        sort($paths);

        return $paths;
    }

    /**
     * Merge all JSON translations for a locale.
     *
     * @param string $locale
     * @return array<string, mixed>
     */
    public static function jsonTranslations(string $locale): array
    {
        $translations = [];

        foreach (static::loaderPaths() as $path) {
            $file = $path . '/' . $locale . '.json';

            if (!file_exists($file)) {
                continue;
            }

            $decoded = json_decode(file_get_contents($file), true);

            if (!is_array($decoded)) {
                continue;
            }

            $translations = array_merge($translations, $decoded);
        }

        return $translations;
    }
}
