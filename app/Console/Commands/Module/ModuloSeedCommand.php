<?php

namespace App\Console\Commands\Module;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Console\Seeds\SeedCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Exception\RuntimeException;

class ModuloSeedCommand extends SeedCommand
{
    protected $signature = 'module:db:seed
        {name : Module name (folder in third-party)}
        {--class= : Seeder class name (without namespace)}
        {--database= : The database connection to use}';

    protected $description = 'Run database seeders for a specific module';

    public function handle(): int
    {
        if ($this->isProhibited() || !$this->confirmToProceed()) {
            return Command::FAILURE;
        }

        $this->components->info('Seeding module database…');

        $previousConnection = $this->resolver->getDefaultConnection();
        $this->resolver->setDefaultConnection($this->getDatabase());

        Model::unguarded(function () {
            $this->getSeeder()->__invoke();
        });

        if ($previousConnection) {
            $this->resolver->setDefaultConnection($previousConnection);
        }

        $this->components->success('Module seeding completed.');

        return Command::SUCCESS;
    }

    /**
     * Resolve the seeder class dynamically from module composer.json
     */
    protected function getSeeder()
    {
        $module = $this->argument('name');
        $class = $this->option('class') ?: 'DatabaseSeeder';

        $modulePath = base_path("third-party/{$module}");

        if (!is_dir($modulePath)) {
            throw new RuntimeException("Module [{$module}] not found in third-party.");
        }

        $composerPath = "{$modulePath}/composer.json";

        if (!file_exists($composerPath)) {
            throw new RuntimeException("composer.json not found for module [{$module}].");
        }

        $composer = json_decode(file_get_contents($composerPath), true);

        $seederNamespace = $this->resolveSeederNamespace($composer);

        $fqcn = $seederNamespace . '\\' . $class;

        if (!class_exists($fqcn)) {
            throw new RuntimeException("Seeder class [{$fqcn}] not found.");
        }

        return $this->laravel->make($fqcn)
            ->setContainer($this->laravel)
            ->setCommand($this);
    }

    /**
     * Detect Database\\Seeders namespace from composer.json
     */
    protected function resolveSeederNamespace(array $composer): string
    {
        $autoload = $composer['autoload']['psr-4'] ?? [];

        foreach ($autoload as $namespace => $path) {
            if (Str::contains($namespace, 'Database\\Seeders')) {
                return rtrim($namespace, '\\');
            }
        }

        throw new RuntimeException(
            'Unable to resolve Database\\Seeders namespace from composer.json'
        );
    }

    protected function configure(): void
    {
        parent::configure();

        $this->setHelp(<<<'HELP'
                The <info>module:db:seed</info> command runs database seeders for a specific module.

                <comment>Usage:</comment>
                php artisan module:db:seed <module> [options]

                <comment>Arguments:</comment>
                <info>name</info>            Module folder name inside third-party/

                <comment>Options:</comment>
                <info>--class</info>         Seeder class name (without namespace)
                <info>--database</info>      Database connection to use

                <comment>Examples:</comment>
                php artisan module:db:seed shop
                php artisan module:db:seed shop --class=CategorySeeder
                php artisan module:db:seed shop --class=CategorySeeder --database=mysql

                <comment>Description:</comment>
                This command dynamically resolves the seeder namespace from the module's
                composer.json file and executes the specified seeder in isolation.
            HELP);
    }

}
