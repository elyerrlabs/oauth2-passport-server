<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Module;

class ModuleAdminTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Module $module)
    {
        $links = [
            'index' => route('admin.modules.index'),
            'enabled' => route('admin.modules.enabled', ['id' => $module->getId()]),
            'disabled' => route('admin.modules.disabled', ['id' => $module->getId()]),
        ];

        if (request()->wantsJson()) {
            $links = [];
        }

        return [
            "id" => $module->id,
            "name" => $module->name,
            "provider" => $module->provider,
            "source" => $module->source,
            "path" => $module->path,
            "username" => $module->username,
            "token" => $module->token,
            "current_version" => $module->current_version,
            "last_version" => $module->last_version,
            "new_version" => $module->new_version,
            "enabled" => $module->enabled,
            "links" => $links
        ];
    }
}
