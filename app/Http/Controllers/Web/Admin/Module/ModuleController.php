<?php

namespace App\Http\Controllers\Web\Admin\Module;

use App\Http\Controllers\WebController;
use Inertia\Inertia;
use App\Services\ModuleService;
use App\Transformers\ModuleAdminTransformer;
use Illuminate\Http\Request;

final class ModuleController extends WebController
{

    public function __construct(protected ModuleService $moduleService)
    {
        parent::__construct();
        $this->middleware("userCanAny:developer:modules:full, developer:modules:view")->only('index');
        $this->middleware("userCanAny:developer:modules:full, developer:modules:install")->only('install');
        $this->middleware("userCanAny:developer:modules:full, developer:modules:uninstall")->only('uninstall');
        $this->middleware("userCanAny:developer:modules:full, developer:modules:update")->only('update');
        $this->middleware("userCanAny:developer:modules:full, developer:modules:enable")->only('enabled');
        $this->middleware("userCanAny:developer:modules:full, developer:modules:disable")->only('disabled');
    }

    /**
     * Index
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $modules = $this->moduleService->search($request);
        $modules = $this->paginate($modules, 20);

        return Inertia::render("Modules/Admin/Index", [
            "data" => transformCollection($modules, ModuleAdminTransformer::class),
            "routes" => [
                'modules' => route('admin.modules.index')
            ]
        ]);
    }

    /**
     * Install module
     * @return void
     */
    public function install()
    {

    }

    /**
     * Update module
     * 
     */
    public function update(Request $request, string $id)
    {

    }

    /**
     * Module enabled
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enabled(string $id)
    {
        $this->moduleService->enabled($id);

        return redirect()->route('admin.modules.index');
    }

    /**
     * Module disabled
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disabled(string $id)
    {
        $this->moduleService->disabled($id);

        return redirect()->route('admin.modules.index');
    }

    /**
     * Uninstall
     * @return void
     */
    public function uninstall()
    {

    }
}
