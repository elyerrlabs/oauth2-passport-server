<?php
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

/**
 * The public.php routes file is intended for defining routes 
 * that need to exist **outside of the module's default URI prefix** 
 * or require a **custom URI prefix** different from the module name.
 * 
 * These routes can be **public** or **protected (authenticated)**, depending on your needs.
 * Use this file when you want more flexibility in route structure without being tied to the module path.
 * 
 * ⚠️ Caution: Overusing public.php or placing unrelated routes here 
 * can lead to route clutter, weak modular boundaries, and maintenance issues.
 * Always evaluate whether a route truly requires a custom/global path.
 */
