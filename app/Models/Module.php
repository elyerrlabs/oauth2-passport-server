<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Arrayable;

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


class Module implements Arrayable
{
    /**
     * Id
     * @var string 
     */
    public $id;

    /**
     * Name
     * @var string 
     */
    public $name;

    /**
     * provider
     * @var string
     */
    public $provider;

    /**
     * Source
     * @var string
     */
    public $source;

    /**
     * Path
     * @var string 
     */
    public $path;

    /**
     * User name
     * @var string
     */
    public $username;

    /**
     * Token
     * @var string
     */
    public $token;

    /**
     * Current version
     * @var string
     */
    public $current_version;

    /**
     * Last version
     * @var string
     */
    public $last_version;

    /**
     * New version
     * @var string
     */
    public $new_version;

    /**
     * Enabled
     * @var bool
     */
    public $enabled;

    /**
     * Construct
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->id = $attributes['id'] ?? (string) Str::uuid();
        $this->name = $attributes['name'] ?? null;
        $this->provider = $attributes['provider'] ?? null;
        $this->source = $attributes['source'] ?? null;
        $this->path = $attributes['path'] ?? null;
        $this->username = $attributes['username'] ?? null;
        $this->token = $attributes['token'] ?? null;
        $this->current_version = $attributes['current_version'] ?? null;
        $this->last_version = $attributes['last_version'] ?? null;
        $this->new_version = $attributes['new_version'] ?? null;
        $this->enabled = $attributes['enabled'] ?? null;
    }

    /**
     * to array 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'provider' => $this->provider,
            'source' => $this->source,
            'path' => $this->path,
            'username' => $this->username,
            'token' => $this->token,
            'current_version' => $this->current_version,
            'last_version' => $this->last_version,
            'new_version' => $this->new_version,
            'enabled' => $this->enabled,
        ];
    }


    /**
     * Get id.
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set id.
     */
    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get name.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set name.
     */
    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get provider.
     */
    public function getProvider(): ?string
    {
        return $this->provider;
    }

    /**
     * Set provider.
     */
    public function setProvider(?string $provider): static
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get source.
     */
    public function getSource(): ?string
    {
        return $this->source;
    }

    /**
     * Set source.
     */
    public function setSource(?string $source): static
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get path.
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * Set path.
     */
    public function setPath(?string $path): static
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get username.
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Set username.
     */
    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get token.
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Set token.
     */
    public function setToken(?string $token): static
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get current version.
     */
    public function getCurrentVersion(): ?string
    {
        return $this->current_version;
    }

    /**
     * Set current version.
     */
    public function setCurrentVersion(?string $version): static
    {
        $this->current_version = $version;

        return $this;
    }

    /**
     * Get last version.
     */
    public function getLastVersion(): ?string
    {
        return $this->last_version;
    }

    /**
     * Set last version.
     */
    public function setLastVersion(?string $version): static
    {
        $this->last_version = $version;

        return $this;
    }

    /**
     * Get new version.
     */
    public function getNewVersion(): ?string
    {
        return $this->new_version;
    }

    /**
     * Set new version.
     */
    public function setNewVersion(?string $version): static
    {
        $this->new_version = $version;

        return $this;
    }

    /**
     * Determine if the module is enabled.
     */
    public function isEnabled(): bool
    {
        return (bool) $this->enabled;
    }

    /**
     * Set enabled state.
     */
    public function setEnabled(bool $enabled): static
    {
        $this->enabled = $enabled;

        return $this;
    }

}