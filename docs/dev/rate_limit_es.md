# Configuración de Rate Limits

El archivo `config/rate_limit.php` define todos los **rate limits** utilizados por un módulo. Cada entrada representa una configuración reutilizable que posteriormente puede aplicarse a cualquier ruta o grupo de rutas mediante el middleware `throttle` de Laravel.

No existe un límite en la cantidad de rate limits que un módulo puede definir. Los desarrolladores pueden crear tantos como sean necesarios.

## Ubicación

```text
config/
└── rate_limit.php
```

## Estructura de la configuración

Cada rate limit contiene las siguientes propiedades:

```php
'web' => [
    'limit'      => 300,
    'block_time' => 120,
    'name'       => 'Rate limit for web routes',
],
```

| Propiedad | Descripción |
| ---------- | ----------- |
| `limit` | Número máximo de solicitudes permitidas. |
| `block_time` | Tiempo (en minutos) durante el cual se bloquearán nuevas solicitudes una vez alcanzado el límite. |
| `name` | Descripción legible utilizada para identificar el rate limit dentro del panel de administración. |

---

## Registro automático

**No es necesario registrar manualmente** los rate limits ni crear una interfaz de administración.

Cuando el módulo es instalado o cargado, Elymod detecta automáticamente todas las entradas definidas en `config/rate_limit.php` y las registra en la aplicación principal.

Posteriormente, los administradores pueden gestionar estos valores desde:

```text
Settings
└── Rate Limits
```

Desde esta sección es posible modificar los valores predeterminados (`limit` y `block_time`) en cualquier momento sin necesidad de cambiar el código fuente del módulo.

Esto permite ajustar los límites de solicitudes en producción sin necesidad de realizar un nuevo despliegue de la aplicación.

---

## Uso de los Rate Limits

Una vez definido un rate limit, puede aplicarse directamente mediante el middleware `throttle`.

Ejemplo:

```php
Route::middleware([
    'throttle:third-party:search-manager:admin'
])->group(function () {

    // Rutas protegidas
});
```

---

## Convención de nombres

Los rate limits siguen una convención de nombres consistente:

```text
throttle:third-party:{module}:{rate_limit}
```

Ejemplo:

```text
throttle:third-party:content:web
```

o

```text
throttle:system:general:public
```

Esta convención evita conflictos de nombres entre módulos y facilita la identificación de cada rate limit dentro del sistema.

---

## Creación de Rate Limits personalizados

Los desarrolladores pueden definir tantos rate limits como sean necesarios.

Ejemplo:

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

Después de agregarlos en `config/rate_limit.php`, Elymod los registrará automáticamente. Estarán disponibles inmediatamente para ser utilizados mediante el middleware `throttle` y podrán administrarse desde **Settings → Rate Limits** sin necesidad de realizar ninguna configuración adicional.