<?php

namespace App\Repositories;

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

use Exception;
use Illuminate\Http\Request;
use App\Models\Setting\Terminal;
use App\Repositories\Contracts\Contracts;
use Elyerr\ApiResponse\Assets\JsonResponser;


class TerminalRepository implements Contracts
{

    use JsonResponser;

    /**
     * Model
     * @var Terminal
     */
    public $model;

    public function __construct(Terminal $terminal)
    {
        $this->model = $terminal;
    }

    /**
     * Search results
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        // Retrieve params of the request
        $params = $this->filter_transform($this->model->transformer);

        // prepare query
        $data = $this->model->query();

        $data->orderByDesc('created_at');

        // Eager loading
        $data = $data->with(['user']);
        // Search
        $data = $this->searchByBuilder($data, $params);

        return $this->showAllByBuilder($data, $this->model->transformer);
    }

    /**
     * Create new resource
     * @param array $data
     * @return void
     */
    public function create(array $data)
    {
        $allowed_commands = $this->model->whiteList();

        $is_allowed = false;
        foreach ($allowed_commands as $allowed) {
            if (str_starts_with($data['command'], $allowed)) {
                $is_allowed = true;
                break;
            }
        }

        if (!$is_allowed) {
            throw new Exception(__('The command is invalid'), 400);
        }

        $exitCode = 0;

        $terminal = $this->model->fill([
            'command' => $data['command'],
            'user_id' => auth()->user()->id
        ]);

        try {

            $output = [];
            exec("cd " . base_path() . " && {$data['command']} 2>&1", $output, $exitCode);

            $terminal->output = json_encode($output);
            $terminal->status = $exitCode === 0;

        } catch (Exception $e) {
            $terminal->output = implode("\n", $e->getTrace());
            $terminal->status = $exitCode === 0;
        }

        $terminal->save();

        return $this->showOne($terminal, $terminal->transformer, 201);
    }

    /**
     * Search specific resource
     * @param string $id
     * @return void
     */
    public function find(string $id)
    {

    }

    /**
     * Update specific resource
     * @param string $id
     * @param array $data
     * @return void
     */
    public function update(string $id, array $data)
    {

    }

    /**
     * Delete specific resource
     * @param string $id 
     * @return void
     */
    public function delete(string $id)
    {

    }

}
