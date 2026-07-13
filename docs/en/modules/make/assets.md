# Available Helpers for Modules

Elymod includes a series of _helpers_ designed to simplify module development. These can be used from controllers, Blade views, Vue components, or any other part of the project as applicable.

---

## `resolveInertiaRoutes(array $items)`

Although its name references Inertia, this helper **does not depend on Inertia**.

It was originally created for projects using Inertia.js, but later began to be used in other modules. For compatibility reasons, the name was kept, as changing it would break existing projects.

Its function is to **resolve only valid routes**, automatically discarding those that do not exist or are not available.

This is especially useful when building dynamic menus.

### Example

In the `menus.php` file:

```php
'vpn_user_routes' => [

    [
        'id' => 'servers',
        'name' => 'Servers',
        'route' => 'module.vpn.web.users.servers.index',
        'icon' => 'mdi mdi-server',
        'service' => 'enterprise:vpn-servers',
        'position' => 1,
    ],

    [
        'id' => 'wireguard',
        'name' => 'WireGuard',
        'route' => 'module.vpn.web.users.wireguards.index',
        'icon' => 'mdi mdi-vpn',
        'service' => 'enterprise:vpn-servers',
        'position' => 2,
    ],

    [
        'id' => 'peers',
        'name' => 'WireGuard Generator',
        'route' => 'module.vpn.web.users.peers.index',
        'icon' => 'mdi mdi-key-chain',
        'service' => 'commerce:vpn',
        'position' => 3,
    ],

];
```

Later, from any controller:

```php
$menus = resolveInertiaRoutes(config('menus.vpn_user_routes'));
```

The helper will return only the routes that exist.

If a route was removed, belongs to a disabled module, or is not registered, it will simply be ignored without throwing exceptions.

### Element Structure

| Field      | Description                                                                                                               |
| ---------- | ------------------------------------------------------------------------------------------------------------------------- |
| `id`       | Unique identifier for the element.                                                                                        |
| `name`     | Text that will be displayed in the menu.                                                                                  |
| `route`    | Name of the route registered in Laravel.                                                                                  |
| `icon`     | Icon that the frontend will use.                                                                                          |
| `service`  | Scope or service required to view the element. Can be specified via `gs_id` or `gsr_id`, for example `commerce:vpn:view`. |
| `position` | Position within the menu. If omitted, the system will use the original array order.                                       |

---

## `module_mix($path, $manifestDirectory = '')`

This is a _fork_ of Laravel's `mix()` helper.

Its functionality is exactly the same, with the difference that it resolves compiled files **within the module** instead of the main project.

Although Elymod uses **Vite** by default, this helper allows using **Laravel Mix** if a module requires it.

### Example

```php
module_mix('css/app.css')

module_mix('js/app.js')
```

---

## `config_module($key = null, $default = null)`

This is equivalent to Laravel's `config()` helper.

Its goal is to simplify access to module configuration without needing to write the full namespace.

For example, instead of writing:

```php
config('third-party.Vpn.server.pro', true);
```

you can use:

```php
config_module('server.pro', true);
```

The helper will automatically resolve the namespace corresponding to the current module.

---

## `syncTranslations(Translatable $translatable, array $attributes)`

This helper automatically syncs translatable fields of a model.

The host system includes native support for translations, so you only need to send the fields following the convention:

```
field
field_es
field_fr
field_pt
...
```

For example:

```text
title
title_es
title_fr

content
content_es
content_fr
```

When the model is retrieved, translated attributes are automatically appended.

Before:

```php
$model = [

    'id' => 1,
    'content' => 'Hello world',

];
```

After:

```php
$model = [

    'id' => 1,

    'content' => 'Hello world',

    'content_es' => 'Hola mundo',

    'content_fr' => 'Bonjour le monde',

];
```

This allows working with translations as if they were normal model attributes.

For more information, see the **Translations** section.

---

## `canAccessMenu(string $scope): bool`

Allows checking whether the authenticated user has a specific permission (_scope_).

This is especially useful for hiding interface elements.

### Blade

```blade
@if(canAccessMenu('administrator:user:create'))

    <button>Create user</button>

@endif
```

### PHP

```php
if (canAccessMenu('commerce:orders:update')) {

    // ...

}
```

### Important

This helper **does not replace** route protection.

Routes must still be protected using middleware and scopes.

The purpose of `canAccessMenu()` is solely to improve user experience by hiding buttons, links, or actions they can never execute.

---

# JavaScript Translations

Laravel includes the helper:

```php
__('Welcome :name', [
    'name' => 'John',
]);
```

Elymod offers the exact same functionality for JavaScript.

```javascript
__("Welcome :name", {
  ":name": "John",
});
```

This allows reusing the same translation files in both PHP and JavaScript.

## Configuration

```javascript
import { setupI18n, __ } from "@/config/locale";

setupI18n();

window.__ = __;
```

## Vue

In Vue applications, it is recommended to register the helper as a global property.

```javascript
app.config.globalProperties.__ = __;
```

It can then be used from any component:

```javascript
this.__("Welcome");

this.__("Hello :name", {
  ":name": "John",
});
```

or directly from templates:

```vue
{{ __("Hello") }}
```

---

# Content Security Policy (CSP) Support

OAuth2 Passport Server includes native support for **Content Security Policy (CSP)**.

When CSP is enabled, all inline scripts and styles must include the `nonce` attribute.

The system automatically exposes the variable:

```blade
{{ $nonce }}
```

which should be used as follows:

```blade
<style nonce="{{ $nonce }}">

</style>

<script nonce="{{ $nonce }}">

</script>
```

## Recommendations

Whenever possible:

- Avoid using inline styles.
- Use CSS classes instead.
- Place JavaScript code inside `<script>` blocks.
- Always add the `nonce` attribute to maintain CSP compatibility.

This way, your module will be fully compatible with installations where CSP policy is enabled, and you will prevent the browser from blocking script or style execution.
