# Rate Limit Configuration

The `config/rate_limit.php` file defines all the **rate limits** used by a module. Each entry represents a reusable configuration that can later be applied to any route or route group through Laravel's `throttle` middleware.

There is no limit to the number of rate limits a module can define. Developers are free to create as many as required.

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

| Property     | Description                                                               |
| ------------ | ------------------------------------------------------------------------- |
| `limit`      | Maximum number of allowed requests.                                       |
| `block_time` | Time (in minutes) that requests will be blocked after reaching the limit. |
| `name`       | Human-readable description displayed in the administration panel.         |

---

## Automatic Registration

There is **no need to manually register** rate limits or create an administration interface.

When the module is installed or loaded, Elymod automatically discovers every entry defined in `config/rate_limit.php` and registers it in the main application.

Administrators can then manage these values from:

```text
Settings
└── Rate Limits
```

From this section, the default values (`limit` and `block_time`) can be modified at any time without changing the module's source code.

This allows production environments to adjust rate limits without requiring a new deployment.

---

## Using Rate Limits

Once a rate limit has been defined, it can be applied directly through the `throttle` middleware.

Example:

```php
Route::middleware([
    'throttle:third-party:search-manager:admin'
])->group(function () {

     // routes here
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

This convention prevents naming conflicts between modules while making rate limits easy to identify.

---

## Creating Custom Rate Limits

Developers may define as many rate limits as needed.

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

After adding them to `config/rate_limit.php`, Elymod automatically registers them. They immediately become available through the `throttle` middleware and can be managed from **Settings → Rate Limits** without any additional configuration.
