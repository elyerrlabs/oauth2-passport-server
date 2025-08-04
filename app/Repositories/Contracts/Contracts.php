<?php
namespace App\Repositories\Contracts;

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

use Illuminate\Http\Request;

interface Contracts
{
    /**
     * Search resources
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function search(Request $request);

    /**
     * Create new resource
     * @param array $data
     * @return void
     */
    public function create(array $data);

    /**
     * Search specific resource
     * @param string $id
     * @return void
     */
    public function find(string $id);

    /**
     * Update specific resource
     * @param string $id
     * @param array $data
     * @return void
     */
    public function update(string $id, array $data);

    /**
     * Delete specific resource
     * @param string $id 
     * @return void
     */
    public function delete(string $id);
}
