<!--
OAuth2 Passport Server — a centralized, modular authorization server
implementing OAuth 2.0 and OpenID Connect specifications.

Copyright (c) 2026 Elvis Yerel Roman Concha

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program. If not, see <https://www.gnu.org/licenses/>.

Author: Elvis Yerel Roman Concha
Contact: yerel9212@yahoo.es

SPDX-License-Identifier: AGPL-3.0-or-later
-->

# 🧑‍💻 Guía de Desarrollo (DEV)

Esta guía describe **exclusivamente el entorno de desarrollo**, pensado para trabajar de forma local, segura y reproducible.

El proyecto funciona **100 % con Docker**, lo que significa que:

- No necesitas instalar PHP, Composer, Node.js, Nginx ni extensiones en tu sistema operativo.
- Todo el stack (PHP, Nginx, Supervisor, Horizon, Node, etc.) vive dentro de contenedores.
- El entorno es idéntico para todos los desarrolladores.

> 🎯 **Objetivo**: que cualquier desarrollador pueda clonar el repositorio y levantar el sistema en minutos, sin configuraciones manuales complejas.

---

## 🌱 Ramas

- **main** → Rama estable (producción)
- **staging** → Rama staging (testing)
- **dev** → Rama de desarrollo activo (**usar esta para trabajar**)

> ⚠️ Todo lo descrito en esta guía asume que estás trabajando sobre la rama `dev`.

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

> ℹ️ `DB_HOST=db` corresponde al nombre del servicio definido en `docker-compose-dev.yml`.

> ℹ️ `APP_KEY` se genera automáticamente durante el primer arranque si está vacío.

---

## 🧩 Configuración del Sistema (Redis, Captcha, etc.)

El sistema está diseñado para que **todas las configuraciones clave sean totalmente parametrizables** y se gestionen desde una **zona central de configuración** dentro de la aplicación.

Desde esta sección podrás configurar de forma sencilla:

- Redis
- Captcha
- Servicios externos
- Parámetros internos del sistema

Todas las opciones están **desacopladas del código**, lo que permite adaptar el comportamiento del sistema sin modificar la lógica interna ni el código fuente.

---

### 🔴 Redis

El sistema **utiliza Redis como componente central** para manejar funcionalidades internas como:

- Cache
- Sesiones
- Colas (Queues)
- Otros mecanismos dependientes del módulo activo

#### Redis en entorno de desarrollo

En el entorno de desarrollo, **ya existe una instancia de Redis ejecutándose dentro del Dev Container**, conectada mediante una **red interna de Docker**.

Esto significa que **no es necesario instalar Redis en el host**.

#### Configuración correcta de Redis

Para que Redis funcione correctamente dentro del contenedor:

1. Dirígete a **Configuración → Redis** dentro de la aplicación.
2. Asegúrate de que **todas las referencias a `127.0.0.1` sean reemplazadas por `redis`**.

> ⚠️ Dentro de Docker, `127.0.0.1` apunta al propio contenedor.
> El nombre correcto del host es **`redis`**, que corresponde al nombre del servicio definido en Docker.

Ejemplo:

```text
Host: redis
Puerto: 6379
```

#### Configuración de colas (Queues) y Horizon

Para habilitar correctamente el procesamiento de colas con Redis:

1. Ve a **Configuración → Queues**.
2. Cambia el **driver de base de datos (`database`) a `redis`**.
3. Guarda los cambios.

Con esta configuración, **Horizon podrá despachar y procesar todas las colas utilizando Redis**, quedando el sistema completamente operativo para el manejo de jobs en segundo plano.

---

> 💡 En desarrollo se recomienda mantener Redis dentro del contenedor para garantizar un entorno reproducible, aislado y consistente con producción.
---

## 🐳 Despliegue en Desarrollo

El proyecto incluye un script que automatiza completamente el despliegue en DEV.

### ¿Qué hace `deploy-dev.sh`?

Este script:

1. Detecta tu UID y GID del host para evitar problemas de permisos.
2. Valida que las variables críticas del `.env` estén definidas.
3. Detiene contenedores existentes.
4. Construye las imágenes necesarias.
5. Levanta todos los servicios con Docker Compose.
6. Instala dependencias de Composer y NPM dentro del contenedor.
7. Deja corriendo los servicios bajo Supervisor.

### Levantar los servicios

```sh
./deploy-dev.sh
```

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

## 🔧 Acceso al Contenedor (cuando sea necesario)

En el flujo normal de trabajo **no es necesario entrar manualmente al contenedor**, ya que la mayoría de las tareas se realizan usando el helper `ops`.

Aun así, existen **dos formas de acceso**, dependiendo de lo que necesites hacer:

---

### 🔴 Acceso como `root` (uso excepcional)

Este modo **solo debe usarse en casos puntuales**, por ejemplo:

- Instalar paquetes del sistema (apk / apt)
- Probar configuraciones del contenedor
- Depuración a bajo nivel

⚠️ **Advertencia importante**:
Si modificas archivos del proyecto como `root`, estos quedarán con permisos de root y **no podrán editarse desde el host**.

👉 Usa este acceso **solo para tareas internas del contenedor**, nunca para editar código del proyecto.

```sh
./opsr bash
```

---

### 🟢 Acceso correcto para desarrollo (recomendado)

Para trabajar con el código, ejecutar Artisan, Composer o NPM, **debes usar el usuario del host (UID/GID)**.

Durante el despliegue (`deploy-dev.sh`) se genera automáticamente un helper local llamado `ops`, que ya maneja esto correctamente.

```sh
./ops bash
```

También puedes ejecutar comandos directamente:

```sh
./ops <comando>
```

Ejemplos:

```sh
./ops php artisan
./ops composer install
./ops npm run watch
```

---

## 🛠️ Comandos Útiles en Desarrollo

### Listar comandos disponibles de Artisan

```sh
./ops php artisan
```

### Crear usuarios del sistema

```sh
./ops php artisan settings:create-user
```

### Instalar dependencias PHP (Composer)

```sh
./ops composer install
```

> ⚠️ Normalmente esto ya lo ejecuta `deploy-dev.sh`.

### Instalar dependencias JavaScript

```sh
./ops npm install
```

### Compilar assets y escuchar cambios

```sh
./ops npm run watch
```

> 💡 Ideal para desarrollo frontend con hot-reload.

---

## 💳 Pagos Recurrentes y Procesos en Background

Los siguientes servicios ya están **gestionados por Supervisor** dentro del contenedor:

- Laravel Horizon
- Workers de colas
- Pagos recurrentes

El comando de pagos recurrentes:

```sh
php artisan payment:charge-recurring
```

👉 **Ya se ejecuta automáticamente**, no necesitas lanzarlo manualmente.

### Ver estado de Supervisor

```sh
./opsr supervisorctl status
```

---

## 🧠 Notas Importantes

- ❌ No ejecutes `php artisan` ni `npm` en el host.
- ✅ Todo debe ejecutarse dentro del contenedor.
- 🔄 Los cambios en el código se reflejan automáticamente.
- 🧪 El entorno DEV está pensado para pruebas y desarrollo, no para producción.

---

## 🚀 Flujo de Trabajo Recomendado

1. Clonar el repo
2. Configurar `.env`
3. Ejecutar `./deploy-dev.sh`
4. Abrir [http://localhost:8001](http://localhost:8001)
5. Programar 🚀

---

### Creación de modulos

- [Leer documentacion](./module/modules_es.md)
