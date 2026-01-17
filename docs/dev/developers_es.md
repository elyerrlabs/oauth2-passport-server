<!--
Copyright (c) 2025 Elvis Yerel Roman Concha

This file is part of an open source project licensed under the
"NON-COMMERCIAL USE LICENSE - OPEN SOURCE PROJECT" (Effective Date: 2025-08-03).

You may use, study, modify, and redistribute this file for personal,
educational, or non-commercial research purposes only.

Commercial use is strictly prohibited without prior written consent
from the author.

Combining this software with any project licensed for commercial use
(such as AGPL) is not permitted without explicit authorization.

This software supports OAuth 2.0 and OpenID Connect.

Author Contact: yerel9212@yahoo.es

SPDX-License-Identifier: LicenseRef-NC-Open-Source-Project
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
git checkout dev
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

### 🌍 (Opcional) Crear un alias global manualmente

Si deseas usar `ops` **desde cualquier directorio del sistema**, puedes crear un alias global en tu shell.

Ejemplo para **bash / zsh**:

```sh
alias ops='docker exec -it --user $(id -u):$(id -g) ops-dev-app-1'
```

Para hacerlo permanente, agrega esa línea en:

- `~/.bashrc`
- `~/.zshrc`

Luego recarga tu shell:

```sh
source ~/.bashrc
# o
source ~/.zshrc
```

A partir de ese momento podrás ejecutar:

```sh
ops php artisan
ops npm run watch
ops sh
```

> ℹ️ Esta configuración es **opcional**. El helper `./ops` local ya cubre el flujo recomendado sin tocar tu sistema.

---

```sh
./ops <comando>
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

### 🔐 Solicitud de Token durante `composer install`

En algunos casos, al ejecutar `composer install`, Composer puede solicitar un **GitHub Access Token** durante la descarga de dependencias.

Esto suele ocurrir cuando:

- Se alcanzan límites de descarga anónima de GitHub.
- Composer intenta descargar dependencias desde repositorios GitHub (por ejemplo, dependencias indirectas como `symfony/mailgun-mailer`).

👉 **Si se te solicita un token**:

1. Genera un token de acceso en tu cuenta de GitHub (no necesita permisos especiales, solo lectura pública).
2. Copia el token generado.
3. Pégalo directamente en la terminal cuando Composer lo solicite.
4. Presiona **Enter** para continuar.

> ℹ️ Composer almacenará el token dentro del contenedor para evitar volver a solicitarlo.
>
> ❓ No es estrictamente claro por qué algunas dependencias como Mailgun lo requieren, pero es un comportamiento normal de Composer al interactuar con GitHub.

---

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

Si algo falla, revisa los logs:

```sh
docker logs -f ops-dev-app-1
```

---

### Creación de modulos

- [Leer documentacion](./module/modules_es.md)
