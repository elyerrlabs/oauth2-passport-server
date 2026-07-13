# Helpers disponibles para módulos

Elymod incorpora una serie de *helpers* diseñados para simplificar el desarrollo de módulos. Estos pueden utilizarse desde controladores, vistas Blade, componentes Vue o cualquier otra parte del proyecto según corresponda.

---

## `resolveInertiaRoutes(array $items)`

Aunque su nombre hace referencia a Inertia, este helper **no depende de Inertia**.

Originalmente fue creado para proyectos que utilizaban Inertia.js, pero posteriormente comenzó a utilizarse en otros módulos. Por motivos de compatibilidad el nombre se mantuvo, ya que cambiarlo rompería proyectos existentes.

Su función consiste en **resolver únicamente las rutas válidas**, descartando automáticamente aquellas que no existan o que no estén disponibles.

Esto resulta especialmente útil al construir menús dinámicos.

### Ejemplo

En el archivo `menus.php`:

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

Posteriormente, desde cualquier controlador:

```php
$menus = resolveInertiaRoutes(config('menus.vpn_user_routes'));
```

El helper devolverá únicamente las rutas que existan.

Si alguna ruta fue eliminada, pertenece a un módulo deshabilitado o no está registrada, simplemente será ignorada sin lanzar excepciones.

### Estructura de un elemento

| Campo      | Descripción                                                                                                                               |
| ---------- | ----------------------------------------------------------------------------------------------------------------------------------------- |
| `id`       | Identificador único del elemento.                                                                                                         |
| `name`     | Texto que se mostrará en el menú.                                                                                                         |
| `route`    | Nombre de la ruta registrada en Laravel.                                                                                                  |
| `icon`     | Icono que utilizará el frontend.                                                                                                          |
| `service`  | Scope o servicio requerido para visualizar el elemento. Puede especificarse mediante `gs_id` o `gsr_id`, por ejemplo `commerce:vpn:view`. |
| `position` | Posición dentro del menú. Si se omite, el sistema utilizará el orden original del arreglo.                                                |

---

## `module_mix($path, $manifestDirectory = '')`

Es un *fork* del helper `mix()` de Laravel.

Su funcionamiento es exactamente el mismo, con la diferencia de que resuelve los archivos compilados **dentro del módulo** en lugar del proyecto principal.

Aunque Elymod utiliza **Vite** por defecto, este helper permite utilizar **Laravel Mix** si un módulo así lo requiere.

### Ejemplo

```php
module_mix('css/app.css')

module_mix('js/app.js')
```

---

## `config_module($key = null, $default = null)`

Es un equivalente al helper `config()` de Laravel.

Su objetivo es simplificar el acceso a la configuración del módulo sin necesidad de escribir el namespace completo.

Por ejemplo, en lugar de escribir:

```php
config('third-party.Vpn.server.pro', true);
```

puedes utilizar:

```php
config_module('server.pro', true);
```

El helper resolverá automáticamente el namespace correspondiente al módulo actual.

---

## `syncTranslations(Translatable $translatable, array $attributes)`

Este helper sincroniza automáticamente los campos traducibles de un modelo.

El sistema host incorpora soporte nativo para traducciones, por lo que únicamente debes enviar los campos siguiendo la convención:

```
campo
campo_es
campo_fr
campo_pt
...
```

Por ejemplo:

```text
title
title_es
title_fr

content
content_es
content_fr
```

Cuando el modelo es recuperado, los atributos traducidos se anexan automáticamente.

Antes:

```php
$model = [

    'id' => 1,
    'content' => 'Hello world',

];
```

Después:

```php
$model = [

    'id' => 1,

    'content' => 'Hello world',

    'content_es' => 'Hola mundo',

    'content_fr' => 'Bonjour le monde',

];
```

Esto permite trabajar con las traducciones como si fueran atributos normales del modelo.

Para más información consulta la sección **Translations**.

---

## `canAccessMenu(string $scope): bool`

Permite comprobar si el usuario autenticado posee un determinado permiso (*scope*).

Es especialmente útil para ocultar elementos de la interfaz.

### Blade

```blade
@if(canAccessMenu('administrator:user:create'))

    <button>Crear usuario</button>

@endif
```

### PHP

```php
if (canAccessMenu('commerce:orders:update')) {

    // ...

}
```

### Importante

Este helper **no reemplaza** la protección de rutas.

Las rutas deben seguir estando protegidas mediante middleware y scopes.

El objetivo de `canAccessMenu()` es únicamente mejorar la experiencia del usuario ocultando botones, enlaces o acciones que nunca podrá ejecutar.

---

# Traducciones en JavaScript

Laravel incorpora el helper:

```php
__('Welcome :name', [
    'name' => 'John',
]);
```

Elymod ofrece exactamente la misma funcionalidad para JavaScript.

```javascript
__('Welcome :name', {
    ':name': 'John',
});
```

Esto permite reutilizar los mismos archivos de traducción tanto en PHP como en JavaScript.

## Configuración

```javascript
import { setupI18n, __ } from "@/config/locale";

setupI18n();

window.__ = __;
```

## Vue

En aplicaciones Vue se recomienda registrar el helper como una propiedad global.

```javascript
app.config.globalProperties.__ = __;
```

Después podrá utilizarse desde cualquier componente:

```javascript
this.__("Welcome");

this.__("Hello :name", {
    ':name': "John",
});
```

o directamente desde las plantillas:

```vue
{{ __('Hello') }}
```

---

# Soporte para Content Security Policy (CSP)

OAuth2 Passport Server incorpora soporte nativo para **Content Security Policy (CSP)**.

Cuando CSP está habilitado, todos los scripts y estilos inline deben incluir el atributo `nonce`.

El sistema expone automáticamente la variable:

```blade
{{ $nonce }}
```

que debe utilizarse de la siguiente forma:

```blade
<style nonce="{{ $nonce }}">

</style>

<script nonce="{{ $nonce }}">

</script>
```

## Recomendaciones

Siempre que sea posible:

* Evita utilizar estilos inline.
* Utiliza clases CSS en su lugar.
* Coloca el código JavaScript dentro de bloques `<script>`.
* Añade siempre el atributo `nonce` para mantener la compatibilidad con CSP.

De esta forma tu módulo será totalmente compatible con instalaciones donde la política CSP se encuentre habilitada y evitarás que el navegador bloquee la ejecución de scripts o estilos.
