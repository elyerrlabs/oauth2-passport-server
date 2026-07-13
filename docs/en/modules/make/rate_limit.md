# Rate Limits Configuration

The `config/rate_limit.php` file defines all **rate limits** used by a module. Each entry represents a reusable configuration that can later be applied to any route or route group using Laravel's `throttle` middleware.

There is no limit on the number of rate limits a module can define. Developers can create as many as needed.

## Location

```text
config/
└── rate_limit.php
```

## Configuration Structure

Each rate limit contains the following properties:

```php
'web' => [
    'limit'      => 300,
    'block_time' => 120,
    'name'       => 'Rate limit for web routes',
],
```

| Property     | Description                                                                            |
| ------------ | -------------------------------------------------------------------------------------- |
| `limit`      | Maximum number of requests allowed.                                                    |
| `block_time` | Time (in minutes) during which new requests will be blocked once the limit is reached. |
| `name`       | Human-readable description used to identify the rate limit within the admin panel.     |

---

## Automatic Registration

**Manual registration is not required** for rate limits, nor is creating an admin interface.

When the module is installed or loaded, Elymod automatically detects all entries defined in `config/rate_limit.php` and registers them with the main application.

Subsequently, administrators can manage these values from:

```text
Settings
└── Rate Limits
```

From this section, it is possible to modify the default values (`limit` and `block_time`) at any time without changing the module's source code.

This allows request limits to be adjusted in production without needing to redeploy the application.

---

## Using Rate Limits

Once a rate limit is defined, it can be applied directly using the `throttle` middleware.

Example:

```php
Route::middleware([
    'throttle:third-party:search-manager:admin'
])->group(function () {

    // Protected routes
});
```

---

## Naming Convention

Rate limits follow a consistent naming convention:

```text
throttle:third-party:{module}:{rate_limit}
```

Example:

```text
throttle:third-party:content:web
```

or

```text
throttle:system:general:public
```

This convention prevents name conflicts between modules and makes it easy to identify each rate limit within the system.

---

## Creating Custom Rate Limits

Developers can define as many rate limits as needed.

Example:

```php
'downloads' => [
    'limit'      => 30,
    'block_time' => 60,
    'name'       => 'Rate limit for file downloads',
],

'contact-form' => [
    'limit'      => 10,
    'block_time' => 30,
    'name'       => 'Rate limit for contact form',
],
```

After adding them to `config/rate_limit.php`, Elymod will automatically register them. They will be immediately available for use via the `throttle` middleware and can be managed from **Settings → Rate Limits** without requiring any additional configuration.
