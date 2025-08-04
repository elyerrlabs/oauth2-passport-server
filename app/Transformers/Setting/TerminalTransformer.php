<?php

namespace App\Transformers\Setting;

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
 */

use App\Models\Setting\Terminal;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class TerminalTransformer extends TransformerAbstract
{
    use Asset;
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
    public function transform(Terminal $terminal)
    {
        return [
            'command' => $terminal->command,
            'output' => json_decode($terminal->output),
            'status' => $terminal->status,
            'user' => [
                'name' => $terminal->user->name,
                'last_name' => $terminal->user->last_name,
                'email' => $terminal->user->email,
            ],
            'created' => $this->format_date($terminal->created_at),
            'updated' => $this->format_date($terminal->updated_at),
            'links' => [
                'index' => route('admin.terminals.index'),
                'execute' => route('admin.terminals.store')
            ]
        ];
    }



    /**
     * Return the original attribute 
     * @param mixed $index
     * @return string|null
     */
    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'id' => 'id',
            'command' => 'command',
            'output' => 'output',
            'status' => 'status',
            'created' => 'created_at',
            'updated' => 'updated_at'
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
