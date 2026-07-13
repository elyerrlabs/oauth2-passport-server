# Configuración del sistema

OAuth2 Passport Server es una plataforma **completamente parametrizable**.

Después de completar la instalación e iniciar sesión por primera vez, se recomienda revisar y configurar las opciones disponibles desde el panel de administración.

La mayoría del comportamiento del sistema puede modificarse desde **Settings**, sin necesidad de editar archivos de configuración ni realizar cambios en el código fuente.

```text
Administrator
└── Settings
```

Cada sección incluye una descripción de su funcionamiento y de los parámetros disponibles.

---

# General

```text
Settings
└── General
```

Permite configurar los parámetros generales del sistema.

Dependiendo de la versión instalada y de los módulos disponibles, aquí podrán encontrarse opciones relacionadas con el comportamiento general de la plataforma.

---

# Session

```text
Settings
└── Session
```

Permite configurar el comportamiento de las sesiones de usuario.

Entre las opciones disponibles se encuentran:

- Tiempo máximo de duración de la sesión.
- Tiempo de inactividad permitido.
- Nombre de las cookies de sesión.
- Configuración relacionada con la autenticación.

Cada parámetro incluye una descripción dentro del panel para facilitar su configuración.

---

# Email

```text
Settings
└── Email
```

Permite configurar el proveedor de correo electrónico utilizado por el sistema.

Esta configuración es necesaria para funcionalidades como:

- Registro de usuarios.
- Recuperación de contraseña.
- Notificaciones por correo.
- Envío automático de credenciales.
- Cualquier módulo que requiera enviar correos electrónicos.

Se recomienda configurar este apartado antes de comenzar a utilizar la plataforma.

---

# Routes

```text
Settings
└── Routes
```

Permite habilitar o deshabilitar rutas registradas por el sistema y por los módulos instalados.

Las rutas disponibles son detectadas automáticamente desde los archivos:

```text
config/routes.php
```

Cada módulo puede registrar sus propias rutas, las cuales aparecerán automáticamente en esta sección para su administración.

---

# Redis

```text
Settings
└── Redis
```

Permite configurar las conexiones Redis utilizadas por la plataforma.

De forma predeterminada se incluyen tres conexiones independientes:

- Lock
- Cache
- Rate Limit

Cada conexión puede utilizar:

- El mismo servidor Redis.
- Distintas bases de datos.
- Servidores Redis completamente diferentes.

La arquitectura queda a criterio del administrador según las necesidades del proyecto.

---

# Cache

```text
Settings
└── Cache
```

Permite seleccionar el driver utilizado por el sistema de caché.

OAuth2 Passport Server soporta múltiples drivers compatibles con Laravel.

Si desea utilizar Redis como sistema de caché, deberá configurar previamente las conexiones correspondientes desde la sección **Redis**.

---

# Horizon

```text
Settings
└── Horizon
```

Permite configurar Laravel Horizon para la ejecución de trabajos en segundo plano mediante Redis.

Desde esta sección es posible definir:

- Workers.
- Colas.
- Balanceo.
- Configuración general de Horizon.

La configuración puede adaptarse a las necesidades de cada entorno.

---

# Queues

```text
Settings
└── Queues
```

Permite seleccionar el driver utilizado por el sistema de colas.

Si se utilizará Laravel Horizon, deberá seleccionarse **Redis** como driver de colas.

Antes de habilitar esta opción, asegúrese de haber configurado correctamente las conexiones Redis.

---

# Scout

```text
Settings
└── Scout
```

Permite configurar Laravel Scout.

Esta funcionalidad resulta especialmente útil para módulos que requieran búsquedas de alto rendimiento.

Por ejemplo:

- Ecommerce.
- CMS.
- Blogs.
- Documentación.
- Inventarios.
- Cualquier módulo que implemente búsquedas indexadas.

Puede utilizar el driver de búsqueda que prefiera, como por ejemplo:

- Meilisearch
- Typesense
- Algolia

o cualquier otro compatible con Laravel Scout.

---

# Rate Limits

```text
Settings
└── Rate Limits
```

Permite administrar todos los Rate Limits registrados en el sistema.

Esta sección incluye tanto los límites definidos por el núcleo como aquellos registrados automáticamente por los módulos instalados.

Desde aquí es posible modificar:

- Número máximo de solicitudes.
- Tiempo de bloqueo.

Sin necesidad de editar archivos de configuración.

---

# Security

```text
Settings
└── Security
```

Centraliza todas las opciones relacionadas con la seguridad del sistema.

Entre ellas se encuentran:

- Configuración de Content Security Policy (CSP).
- Dominios permitidos para el registro de usuarios.
- Edad mínima permitida para el registro.
- Verificación de edad durante el registro.
- Habilitar o deshabilitar el registro mediante comandos.
- Configuración del proveedor CAPTCHA.
- Selección del driver CAPTCHA.
- Habilitar o deshabilitar el usuario Demo.

---

## Usuario Demo

OAuth2 Passport Server incorpora un **usuario Demo** pensado exclusivamente para entornos de demostración y desarrollo.

Cuando esta opción se encuentra habilitada, cualquier persona podrá explorar prácticamente toda la interfaz del sistema sin necesidad de crear una cuenta.

Este usuario puede navegar por las distintas secciones de la plataforma, pero **no puede ejecutar acciones que modifiquen información**, como crear, actualizar o eliminar registros.

Su objetivo es facilitar la presentación del sistema mientras continúa el desarrollo o durante demostraciones públicas.

> **Importante**
>
> No se recomienda habilitar el usuario Demo en entornos de producción.
>
> Si decide utilizar esta funcionalidad, evite almacenar información sensible como credenciales SMTP, claves API, secretos de OAuth o cualquier otra información confidencial, ya que podrá ser visualizada por el usuario Demo.

---

# Configuración modular

Una de las principales ventajas de OAuth2 Passport Server es que la configuración también es modular.

Los módulos pueden registrar automáticamente nuevas secciones dentro del panel **Settings**, integrándose con el resto de la plataforma sin necesidad de modificar el núcleo.

Esto permite que cada módulo disponga de su propia configuración centralizada, manteniendo una experiencia consistente para el administrador del sistema.