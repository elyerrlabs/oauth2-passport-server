# Creación de un nuevo módulo

Crear un módulo en Elymod es un proceso sencillo. Desde la raíz del proyecto **OAuth2 Passport Server**, ejecuta el siguiente comando:

```bash
php artisan module:make ModuleName
```

Reemplaza `ModuleName` por el nombre que tendrá tu módulo.

## Seleccionar la versión de Elymod

Después de ejecutar el comando, el asistente te solicitará seleccionar la versión de **Elymod** que se utilizará como plantilla para generar el módulo.

Utiliza las teclas **↑** y **↓** del teclado para desplazarte entre las versiones disponibles y presiona **Enter** para confirmar la selección.

> **Recomendación:** Utiliza siempre la versión estable más reciente, salvo que necesites mantener compatibilidad con una versión anterior.

## Generación del módulo

Una vez seleccionada la versión, comenzará el proceso de creación del módulo.

Al finalizar, se generará un proyecto completamente funcional dentro del directorio `third-party` ubicado en la raíz del proyecto.

```text
third-party/
└── ModuleName/
```

Todos los módulos desarrollados o instalados se almacenan en este directorio.

El módulo generado incluye una estructura base lista para comenzar a desarrollar, con algunos ejemplos que sirven como referencia, entre ellos:

- Rutas de ejemplo.
- Controladores.
- Archivos de configuración.
- Proveedor de servicios.
- Vistas.
- Traducciones.
- Iconos y elementos de menú de ejemplo.

Estos archivos son únicamente una plantilla inicial y pueden modificarse o eliminarse según las necesidades de tu proyecto.

## Trabajando con un módulo

Un módulo funciona como si fuera un proyecto Laravel independiente ejecutándose dentro del proyecto principal.

Podrás utilizar los comandos de Artisan desde el módulo de la misma forma que lo haces en el proyecto host.

Por este motivo, es importante elegir cuidadosamente el nombre del módulo antes de crearlo.

> **Importante:** El nombre del módulo forma parte de su _namespace_ principal y del sistema de aislamiento de dependencias. Cambiar el nombre posteriormente implica modificar espacios de nombres, configuraciones y referencias en todo el proyecto, por lo que no es una práctica recomendada.

## Administración de dependencias

Dentro de un módulo **no se utiliza Composer directamente**.

En su lugar, Elymod incorpora **elyscope**, una herramienta que reemplaza a Composer para la administración de dependencias dentro del módulo.

ElyScope instala y aísla automáticamente todas las dependencias, evitando conflictos de versiones entre módulos y con el proyecto principal.

Por ello, para instalar, actualizar o eliminar paquetes, siempre debes utilizar **elyscope**.

Una vez instalada una dependencia, su espacio de nombres será aislado automáticamente.

Por ejemplo, en lugar de importar una clase de esta forma:

```php
use Vendor\Package\ClassName;
```

deberás utilizar el _namespace_ aislado del módulo:

```php
use ModuleName\Vendor\Package\ClassName;
```

Este mecanismo permite que diferentes módulos puedan utilizar versiones distintas de una misma librería sin generar conflictos entre ellas.

## Comenzar el desarrollo

Una vez generado el módulo, puedes abrirlo con tu editor o IDE favorito y comenzar a desarrollar.

Cada módulo puede contener sus propios:

- Rutas.
- Controladores.
- Modelos.
- Migraciones.
- Vistas.
- Recursos estáticos.
- Comandos Artisan.
- Configuración.
- Eventos.
- Políticas.
- Traducciones.
- Dependencias.
- Pruebas.

Todo el código permanece completamente aislado del resto de módulos y del proyecto principal.

## Recomendaciones para el desarrollo

Para desarrollar módulos se recomienda trabajar siempre sobre un entorno de desarrollo estable.

En lugar de utilizar los scripts **production** o **staging**, utiliza el script **dev**, ya que está diseñado específicamente para el desarrollo de módulos.

El entorno **dev** mantiene una conexión directa con el proyecto host, lo que facilita el acceso al directorio `third-party` y permite trabajar con los módulos de forma rápida desde tu editor o IDE.

Por el contrario, los entornos **staging** y **production** están orientados a validar y desplegar versiones de **OAuth2 Passport Server**, por lo que no resultan adecuados para el desarrollo diario de módulos.

Utilizar el entorno **dev** proporciona una experiencia de desarrollo mucho más fluida, facilitando las tareas de edición, depuración y pruebas durante todo el ciclo de desarrollo del módulo.
