# Comandos Artisan dentro de un módulo

Al igual que el proyecto principal (host), cada módulo dispone de su propia consola de Artisan.

Puedes ejecutar los comandos desde la raíz del módulo utilizando:

```bash  
php artisan
```

La principal diferencia es que únicamente estarán disponibles los comandos compatibles con el entorno del módulo, entre ellos:

- Comandos `make:*`.
- Comandos relacionados con `api:response`.
- Los comandos propios del módulo.

Esto permite desarrollar un módulo de forma prácticamente independiente del proyecto principal.

---

# Comandos personalizados

Puedes crear comandos Artisan dentro del módulo de la misma forma que en una aplicación Laravel.

Una vez creados, **no es necesario registrarlos manualmente**. Elymod los detecta y registra automáticamente durante el arranque del módulo.

## Convención de nombres

Para evitar conflictos entre módulos, todos los comandos deberían seguir el siguiente formato:

```text
moduleName:module:command
```

Si el comando realiza varias acciones sobre un mismo recurso, es recomendable añadir una acción al final:

```text
moduleName:module:command:action
```

### Ejemplos

```text
elymod-app:module:client:create

elymod-app:module:client:update

elymod-app:module:client:disable

elymod-app:module:client:remove
```

Este formato evita colisiones entre módulos y facilita identificar el origen de cada comando.

---

# Programar tareas (Scheduler)

Los módulos pueden registrar tareas programadas (_Scheduled Tasks_) utilizando el Scheduler de Laravel.

Para ello, simplemente define las tareas dentro del archivo:

```text
routes/console.php
```

Por ejemplo:

```php
use Illuminate\Console\Scheduling\Schedule;

return function (Schedule $schedule) {

    $schedule->command('elymod-app:module:test-command')->everyMinute();

};
```

## Registro automático

Una vez que registres tus tareas en `routes/console.php`, **no es necesario realizar ninguna configuración adicional**.

Durante el arranque de la aplicación, el proyecto host detecta automáticamente los archivos `routes/console.php` de todos los módulos instalados y registra sus tareas dentro del Scheduler de Laravel.

Esto significa que únicamente debes definir las tareas que deseas ejecutar; el resto del proceso es completamente automático.

## Ejecución y supervisión

OAuth2 Passport Server utiliza **Supervisor** para mantener en ejecución el Scheduler de Laravel.

No es necesario:

- Registrar manualmente los comandos.
- Crear procesos adicionales.
- Configurar servicios personalizados.
- Implementar lógica para reiniciar tareas programadas.

Si el proceso encargado del Scheduler se detiene inesperadamente por cualquier motivo (reinicio del servidor, errores del sistema, falta de memoria, etc.), **Supervisor lo iniciará nuevamente de forma automática**, tantas veces como sea necesario para restablecer el servicio.

Como desarrollador del módulo, únicamente debes preocuparte por definir las tareas programadas.

El proyecto host se encargará automáticamente de:

- Detectar las tareas de todos los módulos.
- Registrarlas en el Scheduler de Laravel.
- Ejecutarlas según la programación definida.
- Mantener el Scheduler en funcionamiento.
- Reiniciar automáticamente el proceso si ocurre algún fallo.

Gracias a este mecanismo, todos los módulos comparten una única infraestructura de tareas programadas, simplificando el desarrollo y eliminando la necesidad de realizar configuraciones adicionales.

---

# Middleware

Los middleware funcionan exactamente igual que en Laravel.

Puedes crearlos utilizando Artisan:

```bash
php artisan make:middleware Elymod
```

Una vez creado el middleware, implementa la lógica correspondiente y regístralo en el archivo:

```text
app/Http/Kernel.php
```

Por ejemplo:

```php
return [

    'elymod' => \Elymod\App\Http\Middleware\Elymod::class,

];
```

Después podrás utilizarlo desde cualquier ruta del módulo.

```php 
Route::middleware(['elymod'])->group(function () {

    // ...

});
```

o directamente en un controlador:

```php
$this->middleware('elymod');
```

---

# Traducciones

Las traducciones siguen exactamente la misma estructura que el proyecto principal.

Simplemente crea la carpeta `lang` dentro del módulo y organiza los archivos por idioma.

Por ejemplo:

```text
lang/

├── en.json
│
├── es.json
│
└── fr.json
```

Después podrás utilizar el helper estándar de Laravel:

```php
__('messages.welcome');
```

o el helper disponible en JavaScript:

```javascript
__("messages.welcome");
```

No es necesario realizar ninguna configuración adicional. Elymod detecta automáticamente los archivos de traducción del módulo y los integra con el sistema de internacionalización del proyecto principal.

> **Nota:** Si tu módulo utiliza el sistema de traducciones de modelos (`syncTranslations()`), las traducciones de interfaz (`lang`) y las traducciones de contenido pueden utilizarse conjuntamente sin configuración adicional.
