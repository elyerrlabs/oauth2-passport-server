# 🧑‍💻 Guía de Desarrollo (DEV)

Esta guía describe **exclusivamente el entorno de desarrollo**, diseñado para trabajar de forma local, segura y reproducible.

El proyecto funciona **principalmente mediante Docker**, por lo que prácticamente todo el entorno de ejecución se encuentra aislado dentro de contenedores.

No es necesario instalar en el sistema operativo:

- Composer.
- Node.js.
- Nginx.
- Supervisor.
- Redis.
- MariaDB/MySQL.
- Cualquier otro servicio requerido por la aplicación.

Todo el stack de desarrollo se ejecuta dentro de los contenedores Docker, garantizando que todos los desarrolladores trabajen sobre el mismo entorno.

## PHP en el host

Aunque **no es obligatorio**, se recomienda tener **PHP instalado en el sistema operativo**.

Esto no es necesario para ejecutar el proyecto, sino para que los editores e IDEs puedan utilizar correctamente sus herramientas de desarrollo, como:

- Autocompletado.
- Análisis estático.
- Navegación entre clases.
- Detección de errores.
- Extensiones como **Intelephense**, **PHP Tools** o las integradas en **PhpStorm**.

El código seguirá ejecutándose dentro de Docker; la instalación local de PHP únicamente mejora la experiencia de desarrollo.

> **Objetivo:** que cualquier desarrollador pueda clonar el repositorio, ejecutar el entorno con Docker y comenzar a desarrollar en pocos minutos, manteniendo un entorno consistente y sin depender de configuraciones manuales complejas.

---

## 🌱 Ramas

El proyecto mantiene varias ramas con distintos propósitos:

- **main** → Última versión estable publicada. Es la rama utilizada para producción.
- **staging** → Rama de pruebas donde se validan cambios antes de formar parte de una versión estable.
- **dev** → Rama de desarrollo activo donde se implementan nuevas funcionalidades y cambios que formarán parte de futuras versiones.

### ¿Qué rama debo utilizar?

Depende del tipo de desarrollo que vayas a realizar.

### Desarrollar el proyecto principal

Si vas a contribuir directamente al desarrollo de **OAuth2 Passport Server**, utiliza la rama **dev**, ya que es donde se incorporan las nuevas funcionalidades y correcciones antes de una publicación oficial.

### Desarrollar módulos

Si tu objetivo es desarrollar módulos para Elymod, **no se recomienda trabajar directamente sobre `dev`**.

Lo ideal es utilizar la misma versión estable que tengas desplegada en tu servidor o, como mínimo, una versión compatible.

Por ejemplo, si tu servidor ejecuta la versión `v9.0.0`, puedes crear una rama de trabajo basada en esa versión:

```bash
git checkout -b v9.0.0-dev v9.0.0
```

O si trabajas sobre una instalación `v8.0.1`:

```bash
git checkout -b v8.0.1-dev v8.0.1
```

De esta forma podrás desarrollar y probar tus módulos sobre el mismo código que ejecutará el entorno de producción, evitando incompatibilidades con cambios que aún no han sido publicados.

> **Recomendación:** Mantén una rama de desarrollo basada en la versión estable que utilices habitualmente. Cuando se publique una nueva versión (por ejemplo, `v9.1.0`), revisa las notas de la versión, actualiza tu entorno y crea una nueva rama de desarrollo a partir de ese tag.

---

## ✅ Requisitos Previos

Solo necesitas tener instalado en tu máquina:

- Docker ≥ 24
- Docker Compose (plugin oficial)
- Git

Herramientas opcionales (recomendadas):

- VS Code / PhpStorm
- DBeaver / TablePlus (para la base de datos)

---

## 📦 Clonar el Proyecto

```sh
git clone https://github.com/elyerrlabs/oauth2-passport-server.git
cd oauth2-passport-server
```

---

## ⚙️ Configuración del Entorno

### 1. Archivo `.env`

Copia el archivo de ejemplo:

```sh
cp .env.example .env
```

Ejemplo mínimo recomendado para desarrollo:

```env
APP_ENV=dev
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8001
SCHEMA_HTTPS=http

# LOGS
LOG_CHANNEL=daily
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# DATABASE
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=oauth2
DB_USERNAME=admin
DB_PASSWORD=admin
```

> ℹ️ `DB_HOST=db` corresponde al nombre del servicio definido en `docker-dev.yml`.

> ℹ️ `APP_KEY` se genera automáticamente durante el primer arranque si está vacío.

---

# 🐳 Despliegue del entorno de desarrollo

Levantar el entorno de desarrollo es muy sencillo. Una vez que hayas completado la configuración inicial, únicamente debes ejecutar:

```bash
./dev --deploy
```

Este comando prepara automáticamente todo el entorno de desarrollo y deja el proyecto listo para comenzar a trabajar.

## ¿Qué hace `./dev --deploy`?

Durante el despliegue, el script realiza automáticamente las siguientes tareas:

1. Detecta el **UID** y **GID** del usuario del host para evitar problemas de permisos entre Docker y el sistema operativo.
2. Valida que las variables críticas del archivo `.env` se encuentren configuradas.
3. Detiene los contenedores existentes si están en ejecución.
4. Construye o actualiza las imágenes Docker necesarias.
5. Levanta todos los servicios mediante Docker Compose.
6. Instala las dependencias del proyecto cuando sea necesario.
7. Inicia todos los procesos administrados por **Supervisor**.
8. Verifica que los servicios principales estén funcionando correctamente.

Una vez finalizado el proceso, el entorno estará completamente operativo.

---

# Comandos disponibles

El script `dev` centraliza todas las operaciones relacionadas con el entorno de desarrollo.

| Comando             | Descripción                                                                                                               |
| ------------------- | ------------------------------------------------------------------------------------------------------------------------- |
| `./dev --deploy`    | Construye y despliega el entorno completo de desarrollo.                                                                  |
| `./dev --stop`      | Detiene todos los contenedores del entorno de desarrollo.                                                                 |
| `./dev bash`        | Abre una terminal dentro del contenedor utilizando el usuario configurado para desarrollo.                                |
| `./dev --root bash` | Abre una terminal como **root** dentro del contenedor. Úsalo únicamente cuando necesites realizar tareas administrativas. |

---

# Ejecutar comandos

El script también permite ejecutar comandos directamente dentro del contenedor sin necesidad de acceder primero a una terminal interactiva.

Por ejemplo:

```bash
./dev php artisan migrate
```

```bash
./dev php artisan optimize:clear
```

```bash
./dev php artisan module:list
```

```bash
./dev composer install
```

```bash
./dev npm run dev
```

Aunque esta funcionalidad resulta muy útil para automatizar tareas o ejecutar comandos puntuales, **se recomienda trabajar desde una terminal dentro del contenedor** durante el desarrollo diario.

Para ello, ejecuta:

```bash
./dev bash
```

De esta forma podrás utilizar normalmente herramientas como `php`, `composer`, `npm`, `artisan` o cualquier otro comando disponible en el entorno, sin necesidad de anteponer `./dev` en cada ejecución.

> **Nota:** En la mayoría de los casos no será necesario interactuar directamente con `docker compose`. El script `./dev` abstrae toda la complejidad del entorno y proporciona una interfaz unificada para las tareas habituales de desarrollo.

---

## 🧩 Configuración del sistema

OAuth2 Passport Server ha sido diseñado para que la mayor parte de su configuración pueda administrarse desde la propia aplicación, sin necesidad de modificar archivos de configuración o el código fuente.

Desde el panel de administración podrás configurar, entre otros:

- Redis.
- Captcha.
- Servicios externos.
- Colas (Queues).
- Correo electrónico.
- Caché.
- Sesiones.
- Parámetros generales del sistema.

Esta arquitectura permite adaptar el comportamiento de la aplicación de forma dinámica y centralizada.

---

## Redis

Redis es uno de los componentes principales del sistema y puede utilizarse para diferentes funcionalidades, dependiendo de la configuración elegida.

Entre ellas:

- Caché.
- Sesiones.
- Colas (Queues).
- Horizon.
- Otras características que requieran Redis.

### Entorno de desarrollo

El entorno de desarrollo ya incluye un servicio Redis ejecutándose dentro de Docker.

No es necesario instalar Redis en el sistema operativo.

Para utilizarlo correctamente, accede al panel de administración y configura el servidor Redis desde:

**Configuración → Redis**

La configuración recomendada es:

```text
Host: redis
Puerto: 6379
```

> **Importante:** Si estás ejecutando el proyecto dentro de Docker, no utilices `127.0.0.1` ni `localhost` como servidor Redis. Dentro de un contenedor esas direcciones hacen referencia al propio contenedor y no al servicio Redis.
>
> Utiliza siempre `redis`, que corresponde al nombre del servicio definido en Docker Compose.

---

## Colas (Queues)

Si deseas utilizar Redis para procesar trabajos en segundo plano, accede a:

**Configuración → Queues**

y selecciona:

```text
Driver: redis
```

Guarda los cambios y el sistema comenzará a utilizar Redis como backend para las colas.

Cuando Redis esté configurado como driver de colas, **Laravel Horizon** gestionará automáticamente el procesamiento de los trabajos en segundo plano.

---

## Recomendaciones

- Utiliza siempre el servicio Redis incluido en Docker durante el desarrollo.
- Configura el host como `redis` en lugar de `localhost` o `127.0.0.1`.
- Si utilizas Horizon, asegúrate de que el driver de colas sea `redis`.

De esta forma mantendrás un entorno de desarrollo consistente con producción y evitarás problemas de conectividad entre contenedores.

---

## 🌐 Servicios Disponibles

Una vez levantado el entorno:

- Aplicación web:
  - 👉 [http://localhost:8001](http://localhost:8001)

- PostgreSQL (acceso desde el host):
  - Host: `127.0.0.1`
  - Puerto: `5435`
  - Usuario: `admin`
  - Password: `admin`

---

# 🛠️ Primeros pasos después del despliegue

Una vez que el entorno de desarrollo se haya desplegado correctamente, el siguiente paso será crear un usuario para poder acceder al panel de administración.

## Crear un usuario

Para crear un usuario ejecuta el siguiente comando:

```bash
./dev php artisan settings:create-user
```

El comportamiento del asistente dependerá del entorno configurado en el archivo `.env`.

### Modo desarrollo (`ENV=dev`)

Si el entorno está configurado como:

```text
ENV=dev
```

el asistente de creación funcionará en modo desarrollo.

En este modo únicamente tendrás que seleccionar el **tipo de usuario** que deseas crear.

Utiliza las teclas **↑** y **↓** para seleccionar el tipo de usuario y presiona **Enter**.

El sistema generará automáticamente el resto de la información, incluyendo:

- Nombre.
- Apellidos.
- Correo electrónico.
- Contraseña.

Al finalizar, las credenciales se mostrarán en pantalla para que puedas iniciar sesión inmediatamente.

### Modo producción o staging

Si el entorno está configurado como `production` o `staging`, el asistente solicitará toda la información manualmente.

Entre los datos solicitados se encuentran:

- Nombre.
- Apellidos.
- Correo electrónico.
- Contraseña.
- Tipo de usuario.

Este comportamiento permite crear usuarios reales con la información deseada para entornos que no son de desarrollo.

> **Nota:** El modo `dev` únicamente simplifica el proceso de creación del usuario. El comando sigue siendo el mismo; lo único que cambia es la cantidad de información que el asistente solicita.

---

# Comandos útiles

## Mostrar todos los comandos de Artisan

```bash
./dev php artisan
```

## Crear un usuario

```bash
./dev php artisan settings:create-user
```

## Instalar dependencias de PHP

```bash
./dev composer install
```

> Normalmente no será necesario ejecutar este comando, ya que `./dev --deploy` instala automáticamente todas las dependencias del proyecto.

## Instalar dependencias de JavaScript

```bash
./dev npm install
```

## Ejecutar Vite en modo desarrollo

```bash
./dev npm run dev
```

## Compilar los assets para producción

```bash
./dev npm run build
```

---

# Colas y procesos en segundo plano

El entorno de desarrollo ya incluye todos los procesos necesarios administrados mediante **Supervisor**.

Entre ellos:

- Laravel Horizon.
- Workers de colas.
- Scheduler de Laravel.

No es necesario iniciar ninguno de estos procesos manualmente.

Si deseas comprobar su estado, ejecuta:

```bash
./dev --root supervisorctl status
```

---

# Recomendaciones

- No ejecutes `php`, `composer`, `npm` o `artisan` directamente desde el sistema operativo.
- Ejecuta siempre los comandos mediante `./dev` o desde una terminal abierta con `./dev bash`.
- Los cambios realizados en el código se reflejan automáticamente gracias a los volúmenes compartidos de Docker.
- Utiliza el entorno **DEV** únicamente para desarrollo y pruebas.

---

# Flujo de trabajo recomendado

1. Clonar el repositorio.
2. Configurar el archivo `.env`.
3. Ejecutar `./dev --deploy`.
4. Crear un usuario con `./dev php artisan settings:create-user`.
5. Iniciar sesión con las credenciales generadas.
6. Comenzar a desarrollar.
