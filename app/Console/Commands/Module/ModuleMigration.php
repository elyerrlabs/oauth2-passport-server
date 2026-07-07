<?php

namespace App\Console\Commands\Module;

use App\Repositories\ModuleRepository;
use Illuminate\Console\Attributes\Description;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;


#[Signature('module:migration')]
#[Description('Migrate module to json file from database')]
/**
 * @deprecated since v9.0.0 will be remove in v9.0.1
 */
class ModuleMigration extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!Schema::hasTable('modules')) {
            return;
        }

        $modules = DB::table('modules')->get();

        foreach ($modules as $value) {
            app(ModuleRepository::class)->migrate((array) $value);
        }

        $this->info(__('Database migration successfully'));
    }
}
