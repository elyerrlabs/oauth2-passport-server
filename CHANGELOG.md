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

# Changelog

## 🛠️ v5.0.1

### 🔄 Changed

-   Migrated frontend asset compilation from **Vite** to **Laravel Mix**.
-   Refactored the asset pipeline to align with the modular core architecture.
-   Renamed the **modules** directory to **third-party** to better reflect external module usage.
-   Added a **public/third-party** directory to publish and serve assets from third-party modules.

### 🧹 Removed

-   Removed **Ecommerce** as a core module.

### 🐳 Fixed

-   Fixed and aligned **Docker configuration files** to ensure correct builds and runtime behavior.

### 🛠 Fixed

-   Fixed issues in the **CodeController**.

---

## 🛠️ v4.0.7

### Added

-   Modular asset discovery system: Vite now automatically detects JavaScript and SCSS entrypoints inside each module.
-   Support for multiple JS/SCSS files per module (e.g., `app.js`, `admin.js`, `public.js`).
-   Ability for modules to use Blade views, Vue components, or Inertia pages seamlessly.
-   Fallback system that checks for module-specific assets first, then falls back to the main `resources/` directory.

### Changed

-   Rewritten internal Vite helper logic to dynamically prepend module paths.
-   Updated path resolution to remove absolute base paths and generate clean relative paths (e.g., `core/Ecommerce/resources/js/app.js`).
-   Improved behavior of the `@vite()` directive to remain fully compatible with Laravel defaults while adding module-level resolution.

### Fixed

-   Added validation to ensure only existing module asset files are included.
-   Ensured stable and predictable asset resolution whether routes belong to a module or the main application.
-   Fixed SEO sitemap generator

### Improved

-   Project structure is now fully modularized while maintaining a unified workflow.
-   Modules can now reuse main application components without additional configuration.

---

## 🛠️ v4.0.6

### Fixes

-   Added support for selecting the currency in the eCommerce dashboard.

### Performance & UI Improvements

-   Simplified CSS rules and reduced heavy layout operations on small screens.
-   Removed non-essential shadows, blurs, and transitions that caused frame drops on mobile.
-   Improved responsive behavior for charts and dashboard components.
-   Ensured smoother scrolling and faster interactions on low-end devices.
-   Improve design for eCommerce

### Development

-   Enabled `dotenv` usage inside Vite config.
-   `VITE_HOST` is now used to dynamically define the dev server host.
-   Change applies exclusively to the development environment.

---

## 🛠️ v4.0.5

-   switch to custom Horizon fork with full CSP support

## 🛠️ v4.0.4

### Added

-   New File Service to centralize and manage all file operations.
-   Support to manage refunds
-   Service refunds added
-   New role (review) added
-   Add Auto login support after account creation
-   Updated settings (Payment, Security)

### Improved

-   Image upload performance for products and categories by using temporary storage and internal file moves.
-   Overall file handling flow for faster processing and reduced timeouts.

### Fixed

-   Middleware restriction to block non-GET requests for demo users.
-   Fixed language auto-detection for demo users
-   Fixed Middleware to check demo user
-   Fixed Content Security Policy(CSP)
-   updated eCommerce components
-   Fixed menu ordering inconsistencies.
-   Moved Manage Partner into the Administrator menu.
-   Improved hierarchy and clarity of the navigation structure.
-   Ensured correct visibility and placement of admin-level menu items.

## 🛠️ v4.0.3

-   fix user scope for seo management

## 🛠️ v4.0.2

### Added

-   Added support for sitemaps.
-   Added SEO management section.
-   Added support to edit `robots.txt`.
-   Added real-time backup generation for `robots.txt` and `sitemap.xml`.
-   Added automatic restoration of backed-up SEO files on every Docker container restart or when running `php artisan settings:system-start`.
-   Added Docker volumes for data persistence:
    -   `sitemaps:/var/www/public/sitemaps`
    -   `cache:/var/www/storage/framework/cache`
    -   `logs:/var/www/storage/logs`
-   Added new `deploy-latest` option for deploying the `latest` image tag.
-   Added Content Security Policy exceptions for Horizon, Monaco Editor, and Jodit Editor.

### Improved

-   Improved password update validation request.
-   Updated language files.
-   Updated lang resources.

### Fixed

-   Fixed password update form.
-   Fixed role translation issues.
-   Fixed incorrect key handling for menu rendering in Vue layouts.

---

## 🛠️ v4.0.1

-   Fixed filter for plan search.
-   Added new module for users to view transactions.
-   Refactored eCommerce module layers to delegate responsibilities more clearly.
-   Updated lang
-   Add support for language (English, Spanish)
-   Updated dark mode support

## 🛠️ v4.0.0

-   Added support for module creation
-   Introduced Artisan command for module installation
-   Added license headers to source files
-   Declared `SPDX-License-Identifier: LicenseRef-NC-Open-Source-Project`
-   Documented `/modules` license exception and third-party ownership terms in [LICENSE.md](./LICENSE.md)
-   Clarified that modules are supported but remain subject to their individual licenses
-   Improved price input: now accepts decimal format for readability (still stored internally as integer)
-   Disabled trial functionality (feature not yet available)
-   Renamed **"Client registered"** to **"Secret client"** for confidential client handling
-   Added core modules
-   Improved user interface
-   Enhanced rate limit implementation
-   Improved polymorphism support
-   Introduced e-commerce module
-   Migrated to Vite.js from Laravel Mix
-   Changed Transaction to polymorphic table
-   Added Tags support for polymorphic relation instead of model location
-   Added support for Spanish language
-   Added support for demo user
-   Integrated **queue-based event dispatching** to ensure reliable and asynchronous processing
-   Added **automatic retry mechanism** for failed webhook deliveries
-   Optimized **background jobs with queues**, improving payment and transaction performance
-   Added **foundational support for external service registration**, enabling integration with third-party apps (e.g., Nextcloud)
-   docker configuration updated
-   fixed responsive design

## 🚀 [v3.0.4]

-   Removed orphaned fields to improve data integrity
-   Fixed docker deployment
-   🔒 Changed license to custom non-commercial license

---

## 🚀 [v3.0.3]

-   Improved Docker deployment configuration for enhanced security and consistency.
-   Fixed cache issue affecting system settings persistence.

## 🚀 [v3.0.2]

### 🐛 Fixes

-   Resolved issue where the OpenID Connect endpoint returned static user claims. Now returns dynamic, authenticated user data.

## 🚀 [v3.0.1]

### 👥 Users

-🔧 Fix and properly define the relationship with the Partner model (belongsTo, hasOne, etc.), ensuring it aligns with the business logic.

## 🚀 [v3.0.0]

### 🗄️ Database

-   🏷️ Prefixed all table names with `ops_`
-   ⚙️ Optimized migration structure and logic
-   ❌ Removed `tax` fields from the `transactions` table
-   ❌ Removed `stripe_customer_id` from the `users` table
-   🆕 Added new migration to manage multiple payment providers

### 💳 Payment

-   🛠️ Fixed support for multiple payment providers
-   🔒 Fixed issue with forced activation of payment methods

### 👥 Users

-   🐞 Fixed partner registration with referral code

### 🧩 Middleware

-   🔁 Fixed redirect logic for unauthenticated (guest) users

### 🔐 Login

-   🧭 Fixed redirect behavior after first failed login attempt

### 🧰 Services

-   🔄 Moved `Menu` class from `Models` to `Services`
-   🧹 Refactored `Settings` to follow separation of concerns and moved to `Services`

## [v2.0.2]

### ⚡️ Cache

-   Added cache support for **user scopes**, **menus**, and **configurations**.
-   The section **Admin → Settings → Cache** allows manual management of cached keys.
-   Automatic cache invalidation implemented when scopes expire or related data changes.

### 👮‍♂️ isAdmin (Fix)

-   Fixed `isAdmin` logic to prevent false positives when a user does not belong to an admin group.
-   Now strictly checks against valid groups assigned to the authenticated user.

### 👥 User Groups (Refactor)

-   Unified logic for retrieving all user groups, combining both:
    -   Directly assigned groups (with or without services).
    -   Groups linked through active scopes and services.

### 🚦 Rate Limiting

-   Implemented rate limiting on critical routes to improve security.
-   Added configurable rate limit settings in **Admin → Settings → Security**.

### 🔐 OAuth2

-   Fixed an issue with updating OAuth2 clients in the client management interface.

### 📄 Log Viewer

-   Added an integrated **log viewer** accessible from the admin panel.
-   Enables direct viewing of application logs without accessing the server manually.

### ⚙️ Setting (Fix)

-   Moved `SCHEMA_HTTPS` from dynamic settings to the environment file for better consistency.
-   Fixed deployment issues when running in environments without HTTPS (i.e., HTTP-only setups).

---

## [v2.0.1]

### OAuth2 Enhancements

-   Enhance OAuth2 token validation middleware to strictly verify token integrity and associated client existence, preventing 500 errors when tokens remain active but related clients have been deleted or are missing.

---

## [v2.0.0]

### Framework & Package Upgrades

-   Laravel upgraded from v10 to v12.
-   Laravel Passport upgraded from v10 to v13.

### OAuth2 Enhancements

-   Added internal grant type for trusted applications.
-   Implemented OpenID Connect support.

### User Notifications

-   Added notification UI with read/unread tabs.
-   Notifications mark as read before opening.
-   Badge indicators and modern design using Quasar.

### User Payment

-   Added new functionality to enable or disable recurring payment for the user

### Account Validation

-   Fixed logic related to registration and email verification.

### Developer Route Controls

-   Ability to dynamically enable/disable developer features for users.

### Removed Features

-   Log Viewer removed from admin panel.

---

## [v1.0.0]

### 👤 Users

-   **Dashboard**: User overview panel.
-   **Profile**: Edit personal information.
-   **Password**: Change password functionality.
-   **Two-Factor Authentication (2FA)**: Security feature for login.
-   **Subscriptions**: Manage active user subscriptions.
-   **Store**: Access to service or product purchases.
-   **Developers**:
    -   **Applications**: Manage registered apps.
    -   **API Functionality**: Access and manage API keys and usage.
-   **Partner Portal**:
    -   Accessible only to users with "partner" status.
    -   Partners benefit from services sold to users who register or purchase using their referral link.
    -   Access is granted after the partner has completed at least one purchase.

---

### 🛠️ Admin Panel

-   **User Management**: View, edit, or deactivate user accounts.
-   **Group Management**: Organize users into groups.
-   **Role Management**: define service roles.
-   **Service Management**: Create and manage available services.
-   **Client Management**: Manage application integrations (OAuth clients).
-   **Broadcast Management**: Configure real-time channels.
-   **Plan Management**: Create and manage subscription plans.
-   **Transaction Management**: View and manage user payments.
-   **Command Terminal**: Execute system-level artisan commands.
-   **Settings**:
    -   **General**: General application configuration.
    -   **Session**: App session management.
    -   **Payment**: Configure payment providers (stripe, offline).
    -   **Email**: Manage email service settings.
    -   **Routes**: Manage internal app routes.
    -   **Redis**: Redis connection configuration.
    -   **Cache**: Cache system settings _(currently inactive)_.
    -   **Queue**: Queue service configuration.
    -   **Filesystem**: Storage driver configuration.
    -   **Security**: Configure CAPTCHA and other security-related settings.
    -   **Logs**: Log viewer interface.
