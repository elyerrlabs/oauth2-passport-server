## Creación de módulos

Un **módulo** es una unidad independiente dentro del sistema. Cada módulo cuenta con sus **propias dependencias**, tanto de **PHP (Composer)** como de **Node.js (npm)**, sin interferir con las dependencias del sistema principal.

---

## Comandos disponibles

Para listar todos los comandos disponibles relacionados con módulos:

```bash
./ops php artisan module
```

Salida esperada:

```
Available commands for the "module" namespace:
  module:db:seed  Run database seeders for a specific module
  module:delete   Delete an Elymod module and its published assets symlink
  module:install  Install a third-party module
  module:make     Create a new Elymod module inside third-party directory
```

Para ver la ayuda de un comando específico, usa la siguiente nomenclatura:

```bash
./ops php artisan module:install --help
```

---

## Crear un módulo

Para crear un nuevo módulo:

```bash
./ops php artisan module:make blog
```

Una vez creado, el módulo estará disponible vía web en:

```
http://auth.domain.xyz/blog
```

Todos los módulos se crean automáticamente dentro del directorio:

```
third-party/
```

---

## Acceso al módulo desde el contenedor

Después de crear el módulo, ingresa al contenedor:

```bash
./ops bash
```

Luego navega al directorio del módulo:

```bash
cd third-party/Blog
```

Dentro del módulo puedes usar:

- `php artisan` (con comandos limitados y enfocados al módulo)
- `composer require ...`
- `npm install ...`

Las dependencias instaladas **no entran en conflicto** con el sistema principal. Por defecto, el módulo ya incluye algunas dependencias básicas para facilitar el desarrollo.

---

## Compilación de assets del módulo

Cada módulo maneja sus propios **assets frontend** de forma independiente.

Para trabajar en modo desarrollo y recompilar automáticamente los assets:

```bash
npm run watch
```

Para compilar los assets para producción:

```bash
npm run production
```

Estos comandos utilizan la configuración definida en `webpack.mix.js` del módulo y generan los archivos finales dentro del directorio `public/` del propio módulo.

---

## Artisan dentro del módulo

El comando `php artisan` dentro del módulo expone un conjunto **reducido de comandos**, optimizados para la creación rápida de recursos propios del módulo (controladores, modelos, migraciones, etc.).

---

## Estructura base de un módulo

Ejemplo de estructura generada para el módulo `blog`:

```
blog
├── LICENSE
├── README.md
├── app
│   ├── Http
│   │   ├── Controllers
│   │   │   └── TestController.php
│   │   └── Kernel.php
│   ├── Models
│   │   └── Master.php
│   ├── Providers
│   │   └── ServiceProvider.php
│   └── View
│       └── Components
│           └── Translator.php
├── artisan
├── bootstrap
│   ├── app.php
│   └── cache
│       ├── packages.php
│       └── services.php
├── composer.json
├── composer.lock
├── config
│   ├── menus.php
│   ├── module.php
│   ├── morph.php
│   ├── rate_limit.php
│   └── routes.php
├── database
├── lang
│   ├── en
│   └── es.json
├── package.json
├── public
├── resources
├── routes
├── storage
├── tests
└── webpack.mix.js
```

Esta estructura es **muy similar a un proyecto Laravel estándar**. La principal diferencia es el **Service Provider**, encargado de registrar y cargar todos los recursos del módulo.

⚠️ **Recomendación:** no modificar el `ServiceProvider` a menos que sepas exactamente lo que estás haciendo.

---

## Archivos de configuración

Por defecto, cada módulo incluye los siguientes archivos de configuración:

### `menus.php`

Permite definir y publicar los menús del módulo en las diferentes secciones del panel principal del usuario.

### `module.php`

Contiene las llaves de configuración que permiten **habilitar o deshabilitar** el módulo.

### `morph.php`

Define los **mapeos polimórficos** del módulo. Esto evita el uso de rutas de clase completas y permite usar alias. En caso de reestructurar el módulo, los cambios no afectan al sistema si se mantienen los alias.

### `rate_limit.php`

Permite configurar **limitación de peticiones** por usuario para prevenir abusos o ataques.

- Puedes definir minutos e intentos máximos.
- La configuración **no se aplica automáticamente**.
- Debes asociarla manualmente al grupo de rutas deseado.
- El archivo incluye ejemplos de uso.

### `routes.php`

Permite registrar rutas que pueden ser **activadas o desactivadas** desde la configuración.

⚠️ Nota: las rutas no se deshabilitan automáticamente. Debes agregar la lógica correspondiente para validar la clave de configuración y decidir si la ruta se registra o no.

---

## Resumen

- Cada módulo es independiente.
- Tiene sus propias dependencias PHP y Node.js.
- Se aloja en `third-party/`.
- Se accede vía URL dedicada.
- Mantiene una estructura familiar para desarrolladores Laravel.
- Incluye configuración avanzada para menús, rutas, rate limit y relaciones polimórficas.

---

# Module Creation Guide (English)

## Overview

A **module** is an independent unit within the system. Each module has its **own dependencies**, both **PHP (Composer)** and **Node.js (npm)**, fully isolated from the core application dependencies.

---

## Available Commands

To list all available module-related commands:

```bash
./ops php artisan module
```

Expected output:

```
Available commands for the "module" namespace:
  module:db:seed  Run database seeders for a specific module
  module:delete   Delete an Elymod module and its published assets symlink
  module:install  Install a third-party module
  module:make     Create a new Elymod module inside third-party directory
```

To see help for a specific command, use:

```bash
./ops php artisan module:install --help
```

---

## Creating a Module

To create a new module:

```bash
./ops php artisan module:make blog
```

Once created, the module will be accessible via the web at:

```
http://auth.domain.xyz/blog
```

All modules are created inside the following directory:

```
third-party/
```

---

## Accessing the Module from the Container

After creating the module, enter the container:

```bash
./ops bash
```

Then navigate to the module directory:

```bash
cd third-party/Blog
```

Inside the module you can use:

- `php artisan` (module-scoped commands)
- `composer require ...`
- `npm install ...`

Installed dependencies **do not conflict** with the core system. By default, some base dependencies are already included to speed up development.

---

## Artisan Inside a Module

The `php artisan` command inside a module exposes a **limited and focused set of commands**, designed to quickly generate module-specific resources such as controllers, models, migrations, and more.

---

## Base Module Structure

Example structure for the `blog` module:

```
blog
├── LICENSE
├── README.md
├── app
│   ├── Http
│   │   ├── Controllers
│   │   │   └── TestController.php
│   │   └── Kernel.php
│   ├── Models
│   │   └── Master.php
│   ├── Providers
│   │   └── ServiceProvider.php
│   └── View
│       └── Components
│           └── Translator.php
├── artisan
├── bootstrap
│   ├── app.php
│   └── cache
│       ├── packages.php
│       └── services.php
├── composer.json
├── composer.lock
├── config
│   ├── menus.php
│   ├── module.php
│   ├── morph.php
│   ├── rate_limit.php
│   └── routes.php
├── database
├── lang
│   ├── en
│   └── es.json
├── package.json
├── public
├── resources
├── routes
├── storage
├── tests
└── webpack.mix.js
```

This structure is **very similar to a standard Laravel project**. The main difference is the **Service Provider**, which is responsible for registering and bootstrapping all module resources.

⚠️ **Recommendation:** do not modify the `ServiceProvider` unless you fully understand the implications.

---

## Configuration Files

Each module ships with the following configuration files by default:

### `menus.php`

Defines and publishes module menus across different sections of the main user dashboard.

### `module.php`

Stores configuration keys used to **enable or disable** the module.

### `morph.php`

Defines **polymorphic mappings** for the module. This avoids the use of fully qualified class paths and allows using aliases instead. If the module is restructured, maintaining the aliases prevents breaking changes.

### `rate_limit.php`

Allows configuring **request rate limiting** per user to prevent abuse or attacks.

- Configure max attempts and decay time.
- The configuration is **not applied automatically**.
- You must manually attach it to the desired route group.
- The file includes usage examples.

### `routes.php`

Registers routes that can be **enabled or disabled** via configuration.

⚠️ Note: routes are not automatically disabled. You must implement logic to check the corresponding configuration key and decide whether the route should be registered.

---

## Summary

- Each module is fully independent.
- Has isolated PHP and Node.js dependencies.
- Lives inside `third-party/`.
- Exposed through its own URL.
- Uses a Laravel-familiar structure.
- Includes advanced configuration for menus, routes, rate limiting, and polymorphic relations.
