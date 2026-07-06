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
  module:db:seed           Run database seeders for a specific module
  module:delete            Disable and delete an Elymod module (files only, data preserved by default)
  module:install           Install a third-party module
  module:make              Create a new Elymod module inside third-party directory
  module:services-uploads  loads module services, groups and roles from JSON files in third-party modules
  module:update            Update an existing third-party module

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

Una vez creado, el módulo estará disponible y prodras visaulizar varios accessos directos en el dashboard:

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
- `elyscope require ...`
- `npm install ...`

Las dependencias instaladas **no entran en conflicto** con el sistema principal. Por defecto, el módulo ya incluye algunas dependencias básicas para facilitar el desarrollo.

---

## Compilación de assets del módulo

Cada módulo maneja sus propios **assets frontend** de forma independiente.

Para trabajar en modo desarrollo y recompilar automáticamente los assets:

```bash
npm run build
```

---

## Artisan dentro del módulo

El comando `php artisan` dentro del módulo expone un conjunto **reducido de comandos**, optimizados para la creación rápida de recursos propios del módulo (controladores, modelos, migraciones, etc.).

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
