# Module Development

A **module** is an independent unit within the system. Each module maintains its own **PHP (Composer)** and **Node.js (npm)** dependencies, completely isolated from the main application.

---

# Available Commands

To list all available module-related commands:

```bash
./ops php artisan module
```

Expected output:

```text
Available commands for the "module" namespace:
  module:db:seed           Run database seeders for a specific module
  module:delete            Disable and delete an Elymod module (files only, data preserved by default)
  module:install           Install a third-party module
  module:make              Create a new Elymod module inside the third-party directory
  module:services-uploads  Load module services, groups, and roles from JSON files
  module:update            Update an existing third-party module
```

To display the help for a specific command:

```bash
./ops php artisan module:install --help
```

---

# Creating a Module

Create a new module with:

```bash
./ops php artisan module:make blog
```

Once the command finishes, the module will be available and shortcuts to access it will appear in the dashboard.

All modules are created inside the following directory:

```text
third-party/
```

---

# Accessing the Module

After creating the module, enter the development container:

```bash
./ops bash
```

Navigate to the module directory:

```bash
cd third-party/Blog
```

Inside the module you can use:

- `php artisan` (module-specific Artisan commands)
- `elyscope require ...`
- `npm install ...`

Dependencies installed inside a module are completely isolated from the main application and do not create conflicts. Each module already includes a minimal set of development dependencies to help you get started quickly.

---

# Building Module Assets

Each module manages its frontend assets independently.

For development with automatic recompilation:

```bash
npm run build
``` 

---

# Module Artisan Commands

Running `php artisan` inside a module exposes a reduced set of commands focused on module development, including generators for controllers, models, migrations, and other common resources.

---

# Configuration Files

Every newly created module includes several configuration files.

## `menus.php`

Defines the module menus and publishes them to the appropriate sections of the application's administration panel.

## `module.php`

Contains the module configuration, including the options used to enable or disable the module.

## `morph.php`

Defines the module's polymorphic relation aliases.

Using aliases instead of fully qualified class names makes future refactoring easier without affecting stored data, provided the aliases remain unchanged.

## `rate_limit.php`

Configures request rate limiting to help protect the application from abuse.

Features include:

- Maximum number of attempts
- Time window configuration
- Example configurations

> **Note:** Rate limit definitions are not applied automatically. You must assign them to the desired route groups or middleware.

## `routes.php`

Registers configurable route groups for the module.

> **Note:** Routes are not automatically enabled or disabled. You are responsible for checking the corresponding configuration value before registering them.

---

# Summary

- Every module is fully independent.
- Each module has its own PHP and Node.js dependencies.
- Modules are stored inside `third-party/`.
- Modules can be developed independently from the host application.
- They follow a familiar Laravel project structure.
- Built-in support includes menus, configurable routes, rate limiting, and polymorphic relationship aliases.
