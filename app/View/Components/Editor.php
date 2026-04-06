<?php

namespace App\View\Components;

/**
 * OAuth2 Passport Server — a centralized, modular authorization server
 * implementing OAuth 2.0 and OpenID Connect specifications.
 *
 * Copyright (c) 2026 Elvis Yerel Roman Concha
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * Author: Elvis Yerel Roman Concha
 * Contact: yerel9212@yahoo.es
 *
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Editor extends Component
{
    /**
     *   content
     *
     * @var string
     */
    public $content;

    /**
     *   name
     *
     * @var string
     */
    public $name;

    /**
     *   uid
     *
     * @var string
     */
    public $uid;

    /**
     *   label
     *
     * @var string
     */
    public $label;

    /**
     *   required
     *
     * @var bool
     */
    public $required;

    /**
     *   jodit
     *
     * @var bool
     */
    public $jodit;

    /**
     *   monaco
     *
     * @var bool
     */
    public $monaco;

    /**
     *  preview
     *
     * @var bool
     */
    public $preview;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $content,
        string $name,
        string $label,
        bool $required = false,
        bool $monaco = true,
        bool $jodit = true,
        bool $preview = true
    ) {
        $this->content = $content;
        $this->name = $name;
        $this->label = $label;
        $this->required = $required;
        $this->jodit = $jodit;
        $this->monaco = $monaco;
        $this->preview = $preview;
        $this->uid = 'editor_' . uniqid();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.editor');
    }
}
