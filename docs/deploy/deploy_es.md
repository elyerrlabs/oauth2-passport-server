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

# OAuth2 Passport Server

**Guía de Despliegue en Producción**

---

## 🧑‍💻 Descripción General

Este documento describe cómo desplegar el **OAuth2 Passport Server** en un **entorno de producción** utilizando **Docker**, **Docker Compose** y **Nginx** como proxy inverso.

El sistema está diseñado para ser **modular, configurable y preparado para producción**, con todos los servicios críticos gestionados desde una **configuración centralizada** dentro de la aplicación.

---

## ✅ Requisitos Previos

Antes de comenzar, asegúrate de contar con:

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Nginx](https://nginx.org/) (utilizado como proxy inverso)
- Una **instancia de Redis en ejecución** (requerida para colas y Horizon)

---

## 🌿 Estrategia de Ramas

- **main** — _Estable_
  Refleja la última versión estable lista para producción.

- **vx.x.x** — _Versiones_ (recomenda)
  Etiquetas que siguen versionado semántico para lanzamientos estables.

- **staging** — _Testing_
  Contiene los cambios más recientes para pruebas, se usa para revisar los cambios en un entorno de testing y detectar errorres antes de pasar a entornos reales de produccion y poder corregir cualquier fallo.
  **No debe usarse en producción.**

- **dev** — _Desarrollo_
  Contiene los cambios más recientes para pruebas.
  **No debe usarse en producción.**

---

## ⚙️ Configuración del Sistema (Redis, Captcha, etc.)

El sistema está diseñado para que **todas las configuraciones clave sean completamente parametrizables** y se gestionen desde una **zona central de configuración** dentro de la aplicación.

Desde esta sección podrás configurar:

- Redis
- Proveedores de CAPTCHA
- Servicios externos
- Parámetros internos del sistema

Todas las configuraciones están **desacopladas del código**, permitiendo adaptar el comportamiento del sistema sin modificar la lógica interna.

---

## 🚀 Configuración del Despliegue

### 1️⃣ Clonar el Repositorio

```bash
git clone -b main --single-branch git@github.com:elyerr/oauth2-passport-server.git
cd oauth2-passport-server
```

---

### 2️⃣ Configuración del Entorno

Crea el archivo de entorno para producción: tener en en cuenta que este archivo .env solo sera sera necesario una sola vez para inciar el proyecto , luego de inciar el sistema creara un volumne `env` y almacenara una copia del original, y siempre que escogera primero al al del volumen y si este es eliminado buscara el original, asi que lo recomendable si deceas hacer un cambio en el env luego de iniciar el proyecto debes modificarlo desde el contenedor y editar lo que tengas que editar.

```bash
cp .env.example .env
```

Edita `.env` y ajusta los valores de producción:

```env
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://<tu-dominio.com>
APP_URL_SCHEME=https

# Logs
LOG_CHANNEL=daily
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# Base de datos
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=oauth2
DB_USERNAME=<usuario>
DB_PASSWORD=<contraseña-muy-segura>
```

> ⚠️ Deja `APP_KEY` vacío. El sistema lo generará automáticamente en el primer arranque.

---

## 🐳 Despliegue de la Aplicación (Producción)

Ejecuta el despliegue con:

- para produccion

```bash
./production --deploy
```

- para staging

```bash
./staging --deploy
```

Este script se encarga de:

- Construir los contenedores
- Levantar los servicios
- Inicializar configuraciones del sistema
- Preparar la aplicación para producción

---

## 🚀 Script de Ejecución en Contenedores

| Comando                 | Descripción                                                               |
| ----------------------- | ------------------------------------------------------------------------- |
| `./dev --deploy`        | Despliega el entorno de desarrollo.                                       |
| `./dev --stop`          | Detiene los contenedores de desarrollo.                                   |
| `./dev --root bash`     | Abre una terminal root en el contenedor de desarrollo.                    |
| `./dev bash`            | Abre una terminal con el usuario del entorno de desarrollo.               |
| `./staging --deploy`    | Despliega el entorno de staging.                                          |
| `./staging --stop`      | Detiene los contenedores de staging.                                      |
| `./staging --root bash` | Abre una terminal root en el contenedor de staging.                       |
| `./staging bash`        | Abre una terminal con el usuario del entorno de staging.                  |
| `./production --deploy` | Despliega el entorno de producción.                                       |
| `./production --stop`   | Detiene los contenedores de producción.                                   |
| `./production --root bash` | Abre una terminal root en el contenedor de producción.                 |
| `./production bash`     | Abre una terminal con el usuario del entorno de producción.               |

### Acceso Root

Utilice la opción `--root` para ejecutar comandos como usuario root dentro del contenedor:

```bash
./dev --root bash
./staging --root bash
./production --root bash
```

Se ha creado estos scripts para facilitar la ejecución de comandos dentro del contenedor de la aplicación sin necesidad de escribir manualmente `docker exec`.

El script ejecuta los comandos como el usuario `www-data`, asegurando permisos correctos en el entorno.
 
---

## 👤 Configuración del Primer Usuario

Después del despliegue en producción, debes crear el primer usuario administrador:

```bash
./production php artisan settings:create-user
```

---

## 📦 Comandos de Módulos

El sistema incluye varios comandos Artisan para la gestión de módulos:

| Comando          | Descripción                                                  |
| ---------------- | ------------------------------------------------------------ |
| `module:install` | Instala un módulo de terceros                                |
| `module:delete`  | Elimina un módulo Elymod y su enlace simbólico de assets     |
| `module:db:seed` | Ejecuta los seeders de base de datos de un módulo específico |
| `module:make`    | Crea un nuevo módulo dentro del directorio `third-party`     |

⚠️ **Importante (Producción)**
El comando `module:make` está diseñado **únicamente para desarrollo**.
No se recomienda ejecutarlo en entornos de producción, ya que está pensado para la creación y desarrollo de nuevos módulos, no para su uso operativo.

---

## ✅ Recomendación para Producción

En entornos productivos normalmente solo necesitarás ejecutar comandos usando la siguiente manera:

```bash
./production php artisan r:l
./production php artisan list
./production php artisan module:install
./production php artisan module:delete
./production php artisan module:db:seed
```

---

## 🔄 Actualización a una Nueva Versión

Para actualizar el sistema en producción:

```bash
git pull origin main
./production --deploy
```

---

## 🔴 Redis y Procesamiento de Colas (Horizon)

Redis es un **componente obligatorio en producción** para el uso de:

- Colas (queues)
- Jobs en segundo plano
- Procesos asíncronos

> ⚠️ En producción **NO se levanta Redis mediante Docker**.
> Esto es intencional, ya que el usuario puede disponer de:
>
> - Un servidor Redis dedicado
> - Una instancia Redis compartida
> - Redis gestionado por un proveedor externo

---

### Requisitos de Redis

Asegúrate de contar con:

- Una instancia de Redis en ejecución
- Acceso de red desde el contenedor de la aplicación
- Host, puerto y credenciales (si aplica)

Redis puede estar alojado en:

- El mismo servidor
- Una red interna
- Un proveedor cloud gestionado

---

### Configuración de Redis

1. Accede al **Panel de Administración**.
2. Ve a **Configuración → Redis**.
3. Configura los valores de conexión:

```text
Host: <host-del-servidor-redis>
Puerto: 6379
Usuario: <opcional>
Contraseña: <opcional>
```

Guarda los cambios.

---

### Configuración de Colas (Queues)

Para habilitar el procesamiento de colas:

1. Dirígete a **Configuración → Queues**.
2. Cambia el **driver de colas** de `database` a `redis`.
3. Guarda la configuración.

---

### Funcionamiento de Horizon

Una vez configurados Redis y las colas:

- **Laravel Horizon entra en funcionamiento automáticamente**
- Todas las colas serán despachadas y procesadas usando Redis
- No se requiere configuración adicional en Docker ni en código

Esto habilita:

- Jobs en segundo plano
- Procesos asíncronos
- Ejecución eficiente de tareas

> 💡 Sin Redis correctamente configurado, Horizon no podrá procesar las colas.
> Nota: si todo está configurado correctamente y Horizon no detecta los cambios al momento, espera unos 30 minutos. Si aún sigue sin reiniciarse, la forma más sencilla es reiniciar el contenedor con `./production --deploy` o `./staging --deploy`, según el caso. Esto hará que todos los cambios sean refrescados.

---

## 🌐 Configuración de Nginx (Ejemplo)

```nginx
server {
    listen 80;
    server_name <tu-dominio.com>;

    location / {
        proxy_pass http://127.0.0.1:8001;
        proxy_http_version 1.1;

        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_set_header X-Forwarded-Host $http_x_forwarded_host;
        proxy_set_header X-Forwarded-Port $http_x_forwarded_port;

        proxy_read_timeout 720s;
        proxy_connect_timeout 720s;
        proxy_send_timeout 720s;

        proxy_redirect off;
    }
}
```

---

## 🔐 Regenerar Llaves OAuth2

```bash
php artisan passport:keys --force
```

---

## 🛡 Proveedores de CAPTCHA

### hCaptcha

Alternativa enfocada en la privacidad
[https://dashboard.hcaptcha.com/signup](https://dashboard.hcaptcha.com/signup)

### Cloudflare Turnstile

Verificación sin CAPTCHA tradicional
[https://dash.cloudflare.com/](https://dash.cloudflare.com/)

---

## 📄 Licencia

Este proyecto está licenciado bajo la **GNU Affero General Public License (AGPL)**.

Cualquier modificación, despliegue o uso en red de este software **debe cumplir con los términos de la AGPL**, incluyendo la obligación de poner a disposición el código fuente de las versiones modificadas a los usuarios que interactúen con el sistema a través de una red.

Consulta el archivo `LICENSE` para más detalles.

---

© 2025 Elvis Yerel Roman Concha
Publicado bajo la **GNU Affero General Public License (AGPL)**
