# Creating a New Module

Creating a module in Elymod is a straightforward process. From the root of the **OAuth2 Passport Server** project, run the following command:

```bash
php artisan module:make ModuleName
```

Replace `ModuleName` with the name you want for your module.

## Select the Elymod Version

After running the command, the wizard will prompt you to select the **Elymod** version to be used as a template for generating the module.

Use the **↑** and **↓** arrow keys to scroll through the available versions and press **Enter** to confirm your selection.

> **Recommendation:** Always use the latest stable version, unless you need to maintain compatibility with an older version.

## Module Generation

Once the version is selected, the module creation process will begin.

Upon completion, a fully functional project will be generated inside the `third-party` directory located at the project root.

```text
third-party/
└── ModuleName/
```

All developed or installed modules are stored in this directory.

The generated module includes a base structure ready to start developing, with some examples that serve as a reference, including:

- Example routes.
- Controllers.
- Configuration files.
- Service provider.
- Views.
- Translations.
- Example icons and menu items.

These files are only an initial template and can be modified or removed according to your project's needs.

## Working with a Module

A module works as if it were an independent Laravel project running inside the main project.

You can use Artisan commands from the module the same way you do in the host project.

For this reason, it is important to choose the module name carefully before creating it.

> **Important:** The module name is part of its main _namespace_ and the dependency isolation system. Changing the name later involves modifying namespaces, configurations, and references throughout the project, so it is not a recommended practice.

## Dependency Management

Inside a module, **Composer is not used directly**.

Instead, Elymod incorporates **elyscope**, a tool that replaces Composer for dependency management within the module.

ElyScope automatically installs and isolates all dependencies, avoiding version conflicts between modules and with the main project.

Therefore, to install, update, or remove packages, you must always use **elyscope**.

Once a dependency is installed, its namespace will be automatically isolated.

For example, instead of importing a class like this:

```php
use Vendor\Package\ClassName;
```

you must use the module's isolated namespace:

```php
use ModuleName\Vendor\Package\ClassName;
```

This mechanism allows different modules to use different versions of the same library without generating conflicts between them.

## Start Development

Once the module is generated, you can open it with your favorite editor or IDE and start developing.

Each module can contain its own:

- Routes.
- Controllers.
- Models.
- Migrations.
- Views.
- Static assets.
- Artisan commands.
- Configuration.
- Events.
- Policies.
- Translations.
- Dependencies.
- Tests.

All code remains completely isolated from other modules and from the main project.

## Development Recommendations

For developing modules, it is recommended to always work on a stable development environment.

Instead of using the **production** or **staging** scripts, use the **dev** script, as it is specifically designed for module development.

The **dev** environment maintains a direct connection with the host project, making it easy to access the `third-party` directory and work with modules quickly from your editor or IDE.

On the other hand, the **staging** and **production** environments are intended for validating and deploying **OAuth2 Passport Server** versions, so they are not suitable for daily module development.

Using the **dev** environment provides a much smoother development experience, making editing, debugging, and testing tasks easier throughout the entire module development cycle.