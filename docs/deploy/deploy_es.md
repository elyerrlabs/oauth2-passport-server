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
# Guía de Despliegue en Entorno de Producción

Esta guía explica cómo desplegar el OAuth2 Passport Server en un entorno de producción utilizando Docker, Docker Compose y Nginx.

## Requisitos Previos

-   [Docker](https://docs.docker.com/get-docker/)
-   [Docker Compose](https://docs.docker.com/compose/install/)
-   [Nginx](https://nginx.org/) (utilizado como proxy inverso)

## Resumen de Ramas

- **main**: _[Estable]_ Rama que refleja la última versión estable de la aplicación.
- **vx.x.x**: _[Versión]_ Etiquetas siguiendo el versionado semántico, representando lanzamientos estables.
- **dev**: _[Desarrollo]_ Rama que contiene los cambios más recientes para pruebas; no está destinada para uso en producción.

## Configuración del Despliegue

1. Clona el repositorio:

    ```sh
    git clone git@github.com:elyerr/oauth2-passport-server.git
    cd oauth2-passport-server
    ```

2. Prepara tu configuración de entorno:

    ```sh
    cp .env.example .env
    ```

    Actualiza el archivo `.env` para tu configuración de producción.

    ```env
    APP_ENV=production
    APP_KEY= # Déjalo vacío; el sistema lo generará automáticamente al iniciar.
    APP_DEBUG=false
    APP_URL=https://<tu-dominio.com>
    FRONTEND_URL="${APP_URL}"
    ASSET_URL="${APP_URL}"
    SCHEMA_HTTPS=https

    # Registros
    LOG_CHANNEL=daily
    LOG_DEPRECATIONS_CHANNEL=null
    LOG_LEVEL=debug

    # Base de Datos
    DB_CONNECTION=pgsql
    DB_HOST=db
    DB_PORT=5432
    DB_DATABASE=oauth2
    DB_USERNAME=<establece-el-usuario>
    DB_PASSWORD=<contraseña-muy-segura>
    ```

## Despliegue de la Aplicación

### Entorno de Producción

Despliega tu aplicación en producción ejecutando:

```bash
./deploy-prod.sh
```

### Entorno de Desarrollo

Para propósitos de desarrollo o pruebas (usando la rama dev):

```bash
./deploy-dev.sh
```

## Configuración del Primer Usuario

Después del despliegue, crea el primer usuario con el siguiente comando:

### Producción:

```bash
docker exec -it oauth2-server-app-1 php artisan settings:create-user
```

### Desarrollo:

```bash
docker exec -it oauth2-server-dev-app-1 php artisan settings:create-user
```

## Actualización a una Nueva Versión

### Entorno de Producción

Actualiza tu aplicación en producción ejecutando:

```bash
git pull origin main && ./deploy-prod.sh
```

### Entorno de Desarrollo

Para propósitos de desarrollo o pruebas (usando la rama dev):

```bash
git pull origin dev && ./deploy-dev.sh
```

## Configuración del Proxy Nginx (Ejemplo Básico)

A continuación se muestra una configuración de ejemplo para utilizar Nginx como proxy inverso. Puedes emplear Let's Encrypt para obtener certificados SSL válidos:

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
          proxy_set_header X-Forwarded-AWS-ELB $http_x_forwarded_aws_elb;

          proxy_read_timeout 720s;
          proxy_connect_timeout 720s;
          proxy_send_timeout 720s;

          proxy_buffering on;
          proxy_buffer_size 128k;
          proxy_buffers 4 256k;
          proxy_busy_buffers_size 256k;
          proxy_temp_file_write_size 256k;

          proxy_redirect off;
     }
}
```

## Notas

### Regenerar llaves OAuth2

Para regenerar las llaves de OAuth2, sigue estos pasos:  
1. Accede a la terminal del panel de administración.  
2. Ejecuta el siguiente comando:  

```bash
php artisan passport:keys --force
```

## Métodos de Pago

### Stripe

- **Webhook (POST):** `https://domain.com/webhook/stripe`
- **Eventos gestionados:**
  - `checkout.session.completed`
  - `payment_intent.payment_failed`
  - `checkout.session.expired`
  - `charge.succeeded`
    - `charge.refunded`

### Pago Offline

- **Offline:** Compatible con métodos de pago manuales.

> **Nota:** La renovación automática está habilitada para todos los métodos de pago, excepto en pagos Offline.  
> Configura las opciones de renovación desde el Panel de Administración en **Ajustes → Pago → Renovación**.
 
## Proveedores de CAPTCHA

Protege tus formularios y evita el spam con las siguientes opciones de CAPTCHA:

### hCaptcha

- Alternativa enfocada en la privacidad frente a reCAPTCHA.
- Plan gratuito generoso.
- [Obtén tu clave del sitio](https://dashboard.hcaptcha.com/signup)

### Cloudflare Turnstile

- Verificación de usuarios sin CAPTCHAs tradicionales.
- Experiencia fluida y fácil de usar.
- [Obtén tu clave del sitio](https://dash.cloudflare.com/)

### Configuración

Para activar el proveedor de CAPTCHA de tu preferencia:
1. Ve a **Admin → Ajustes → Seguridad**.
2. Selecciona tu proveedor deseado (hCaptcha o Turnstile).

El sistema renderizará automáticamente el CAPTCHA elegido en los formularios del frontend.
