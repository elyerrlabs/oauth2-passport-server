# System Settings

OAuth2 Passport Server is a **fully configurable** platform.

After completing the installation and signing in for the first time, it is recommended to review and configure the available options from the administration panel.

Most of the platform's behavior can be customized through the **Settings** panel without modifying configuration files or changing the source code.

```text
Administrator
└── Settings
```

Each section includes a description of its purpose and the available configuration options.

---

# General

```text
Settings
└── General
```

Configure the platform's general settings.

Depending on the installed version and enabled modules, this section may contain additional options that control the overall behavior of the system.

---

# Session

```text
Settings
└── Session
```

Configure how user sessions are managed.

Available options include:

- Session lifetime.
- Idle timeout.
- Session cookie name.
- Authentication-related settings.

Each option includes an inline description to simplify configuration.

---

# Email

```text
Settings
└── Email
```

Configure the email provider used by the platform.

This configuration is required for features such as:

- User registration.
- Password recovery.
- Email notifications.
- Automatic delivery of user credentials.
- Any module that sends email.

It is recommended to configure the email service before using the platform in production.

---

# Routes

```text
Settings
└── Routes
```

Enable or disable routes registered by the core application and installed modules.

Available routes are automatically discovered from:

```text
config/routes.php
```

Each module can register its own routes, which will automatically appear in this section for administration.

---

# Redis

```text
Settings
└── Redis
```

Configure the Redis connections used throughout the platform.

By default, OAuth2 Passport Server provides three independent Redis connections:

- Lock
- Cache
- Rate Limit

Each connection can be configured to use:

- The same Redis server.
- Different Redis databases.
- Completely separate Redis servers.

This flexibility allows administrators to adapt the architecture to the needs of their environment.

---

# Cache

```text
Settings
└── Cache
```

Select the cache driver used by the application.

OAuth2 Passport Server supports all cache drivers compatible with Laravel.

If Redis is selected as the cache driver, make sure the Redis connections have been configured first.

---

# Horizon

```text
Settings
└── Horizon
```

Configure Laravel Horizon for processing background jobs using Redis.

Available options include:

- Workers.
- Queues.
- Balancing strategies.
- General Horizon configuration.

The configuration can be adjusted according to the workload and deployment environment.

---

# Queues

```text
Settings
└── Queues
```

Select the queue driver used by the application.

If you plan to use Laravel Horizon, the queue driver must be set to **Redis**.

Before enabling Redis queues, ensure the Redis connections have been configured correctly.

---

# Scout

```text
Settings
└── Scout
```

Configure Laravel Scout.

Scout is particularly useful for modules that require fast and scalable full-text search capabilities.

Typical use cases include:

- E-commerce.
- CMS platforms.
- Blogs.
- Documentation.
- Inventory systems.
- Any module requiring indexed searches.

You may choose any search engine supported by Laravel Scout, including:

- Meilisearch
- Typesense
- Algolia

or any other compatible Scout driver.

---

# Rate Limits

```text
Settings
└── Rate Limits
```

Manage all rate limit definitions registered in the platform.

This section includes both:

- Limits provided by the core application.
- Limits automatically registered by installed modules.

Administrators can modify:

- Maximum number of requests.
- Block duration.

without editing configuration files.

---

# Security

```text
Settings
└── Security
```

Centralizes all security-related settings.

Available options include:

- Content Security Policy (CSP).
- Allowed email domains for user registration.
- Minimum registration age.
- Age verification during registration.
- Enable or disable user registration through Artisan commands.
- CAPTCHA provider configuration.
- CAPTCHA driver selection.
- Enable or disable the Demo User.

---

## Demo User

OAuth2 Passport Server includes a built-in **Demo User**, intended exclusively for demonstrations and development environments.

When enabled, visitors can explore most areas of the application without creating an account.

The Demo User has read-only access and cannot perform actions that modify data, such as creating, updating, or deleting records.

This feature is particularly useful for public demonstrations, product evaluations, or development environments.

> **Important**
>
> The Demo User should not be enabled in production environments.
>
> If you choose to enable this feature, avoid storing sensitive information such as SMTP credentials, API keys, OAuth secrets, or any other confidential configuration that could be visible to the Demo User.

---

# Modular Configuration

One of the key features of OAuth2 Passport Server is its modular configuration system.

Modules can automatically register new sections within the **Settings** panel without modifying the application's core.

This allows every module to provide its own centralized configuration while maintaining a consistent administration experience across the entire platform.
