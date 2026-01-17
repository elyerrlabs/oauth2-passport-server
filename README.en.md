<!--
OAuth2 Passport Server — a centralized, modular authorization server
implementing OAuth 2.0 and OpenID Connect specifications.

Copyright (c) 2026 Elvis Yerel Roman Concha

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program. If not, see <https://www.gnu.org/licenses/>.

Author: Elvis Yerel Roman Concha
Contact: yerel9212@yahoo.es

SPDX-License-Identifier: AGPL-3.0-or-later
-->
# OAuth2 Passport Server

A **modular OAuth2 & OpenID Connect authorization platform** designed for modern, scalable systems. This project is not just an authorization server, but the **core backend of a modular ecosystem** powered by **Elymod**, a lightweight framework built specifically for creating independent, installable modules.

This repository acts as the **central authorization backend**, while features, services, and extensions are delivered through modules.

---

## What Makes This Different?

Unlike traditional Laravel module packages that simply reorganize a monolithic application into folders, this system was designed from day one to be **truly modular**.

Key differences:

* Modules are **independent packages**, not just directories
* Each module can have its own:

  * Controllers
  * Routes
  * Migrations
  * Seeders
  * Assets
  * Composer dependencies
* The core remains clean and minimal
* Features are added by **installing modules**, not modifying the core

This approach avoids the typical “modular monolith” problem and enables long-term scalability.

---

## Elymod Framework

This system is built on **Elymod**, a lightweight modular framework composed of:

* **Laravel Runtime** – a minimal Laravel kernel exposing only what modules need
* **Elymod** – the modular layer that manages:

  * Module discovery
  * Module bootstrapping
  * Asset publishing
  * Command registration
  * Database integration

Together, they form a **focused framework for building modular backends**, not general-purpose applications.

---

## Core Features

### OAuth2 & OpenID Connect

* Full OAuth2 support via Laravel Passport
* OpenID Connect compatible
* Secure token issuance and validation
* Ideal for first-party and third-party applications

### Advanced Scope System

Instead of basic role-based systems, this platform introduces a **structured scope model**:

* **Groups** – logical domain separation
* **Services** – application or API-level access
* **Roles** – fine-grained permissions

This model allows:

* Better separation of concerns
* Cleaner authorization rules
* Easier integration with external applications

### Modular Extensibility

* Add features via modules
* Enable or disable functionality without touching the core
* Extend authentication, authorization, APIs, dashboards, or integrations

---

## Creating Your First Module

### 1. Create a Module

```bash
php artisan module:make shop
```

This will generate a new module inside:

```text
third-party/shop
```

Each module is treated as an independent package.

---

### 2. Module Access (Routing)

Modules are automatically exposed using their **prefix name**.

Example:

```text
https://www.site.dev/shop
```

No additional route registration is required.

---

### 3. Database Migrations

There is **no separate migration command per module**.

When a module is active:

```bash
php artisan migrate
```

* Executes migrations from the core
* Executes migrations from all active modules

By default, **all module tables are prefixed with the module name**, preventing collisions and improving clarity.

---

## Why This Architecture Matters

This system is designed to:

* Scale without becoming unmaintainable
* Encourage clean boundaries between features
* Support internal and third-party integrations
* Act as a foundation for an **Application Store** concept

Future goal:

> A built-in App Store that automatically discovers and installs modules from a central repository.

---

## Resources

* [Documentation](https://gitlab.com/elyerr/oauth2-passport-server/-/wikis/home)
* [API Documentation](https://documenter.getpostman.com/view/5625104/2sB2xBDq6o)
* [Echo Server](https://gitlab.com/elyerr/echo-server) (coming soon)
* [Echo Client](https://gitlab.com/elyerr/echo-client-js) (coming soon)

---

## Deployment Guides

* [English Deployment Guide](./docs/deploy/deploy_en.md)
* [Spanish Deployment Guide](./docs/deploy/deploy_es.md)

## Developer Guides

* [English Developer Guide](./docs/dev/developers_en.md)
* [Spanish Developer Guide](./docs/dev/developers_es.md)

---

## 📄 License

This project is licensed under the
**GNU Affero General Public License v3.0 (AGPL-3.0)**.

You are free to use, study, modify, and redistribute this software under the terms of the AGPL-3.0.
If you run or offer this software as a network service, you must provide access to the complete
corresponding source code, including any modifications.

See the [LICENSE](LICENSE.md) file for full license details.

---

## Community & Contact

This project is part of the **ElyerrLabs ecosystem**.

* Telegram: [https://t.me/elyerr](https://t.me/elyerr)

If you are interested in contributing, building modules, or helping grow the ecosystem, you are welcome.
