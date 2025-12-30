<!--
Copyright (c) 2025 Elvis Yerel Roman Concha

This file is part of an open source project licensed under the
"NON-COMMERCIAL USE LICENSE - OPEN SOURCE PROJECT" (Effective Date: 2025-08-03).

You may use, study, modify, and redistribute this file for personal,
educational, or non-commercial research purposes only.

Commercial use is strictly prohibited without prior written consent
from the author.

Combining this software with any project licensed for commercial use
(such as AGPL) is not permitted without explicit authorization.

This software supports OAuth 2.0 and OpenID Connect.

Author Contact: yerel9212@yahoo.es

SPDX-License-Identifier: LicenseRef-NC-Open-Source-Project
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
php artisan module:create shop
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

## License

This project is licensed under a **Non-Commercial Use License**.

Commercial usage requires prior written authorization.

See [LICENSE.md](LICENSE.md) for details.

---

## Community & Contact

This project is part of the **ElyerrLabs ecosystem**.

* Telegram: [https://t.me/elyerr](https://t.me/elyerr)

If you are interested in contributing, building modules, or helping grow the ecosystem, you are welcome.
