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
# OAuth2 Passport Server

Un **servidor de autorización centralizado, modular y extensible**, construido sobre Laravel Passport, diseñado para funcionar como el núcleo de autenticación y autorización de múltiples aplicaciones y servicios modernos.

Este proyecto no es solo una aplicación Laravel tradicional: está basado en un **sistema de módulos reales**, impulsado por un mini‑framework propio compuesto por **Elymod** y **Laravel Runtime**, que permite desarrollar, aislar y escalar funcionalidades sin caer en un monolito acoplado.

---

## 🧩 Arquitectura Modular Real

A diferencia de otros paquetes de “módulos” que solo reorganizan carpetas dentro de un monolito, este proyecto introduce un **enfoque modular desacoplado desde el runtime**:

* Cada módulo es **independiente**, con su propio código, rutas, vistas, migraciones y assets.
* Los módulos se cargan dinámicamente mediante **Elymod**.
* **Laravel Runtime** actúa como puente ligero, exponiendo únicamente las partes necesarias del framework.
* El core permanece pequeño, estable y fácil de mantener.

Este enfoque permite:

* Escalar funcionalidades sin aumentar la complejidad del core.
* Activar o desactivar módulos sin romper el sistema.
* Desarrollar features como productos independientes.
* Facilitar contribuciones de terceros y crecimiento de comunidad.

---

## 🚀 Creación de Módulos

Para crear un nuevo módulo:

```bash
php artisan module:create shop
```

Esto generará la estructura completa del módulo.

Luego, muévete al directorio de módulos:

```bash
cd third-party/shop
```

Desde ahí puedes desarrollar el módulo de forma totalmente aislada.

### Acceso por URL

Cada módulo expone sus rutas automáticamente usando un **prefijo basado en su nombre**:

```
https://www.site.dev/shop
```

No se requieren configuraciones adicionales.

---

## 🗄️ Migraciones

No existe un comando de migraciones por módulo.

Cuando un módulo está activo:

```bash
php artisan migrate
```

Laravel ejecutará automáticamente **todas las migraciones de todos los módulos activos**, como parte del flujo normal.

### Prefijo de Tablas

Por defecto, **todas las tablas de un módulo usan el nombre del módulo como prefijo**, por ejemplo:

```
shop_products
shop_orders
```

Esto evita colisiones entre módulos y mantiene una base de datos organizada y predecible.

---

## 🔐 Sistema Avanzado de Autorización (Scopes)

Este servidor va mucho más allá de los sistemas clásicos de roles y permisos.

Implementa un **sistema de scopes estructurado**, compuesto por:

* **Grupos** – Contextos de autorización
* **Servicios** – Capacidades expuestas
* **Roles** – Acceso específico dentro de cada servicio

Este modelo permite:

* Diseñar políticas de acceso complejas sin acoplarlas a usuarios.
* Conectar múltiples aplicaciones con distintos niveles de permisos.
* Evitar sistemas rígidos de roles globales.

El resultado es un sistema de autorización **mucho más flexible, escalable y orientado a servicios**.

---

## 🌐 Integración con Aplicaciones de Terceros

El servidor soporta de forma nativa:

* **OAuth2**
* **OpenID Connect**

Esto permite:

* Conectar aplicaciones externas sin compartir credenciales.
* Centralizar autenticación para microservicios y frontends.
* Usar el servidor como proveedor de identidad (IdP).

Además, los **módulos** pueden extender el sistema para:

* Nuevos flujos de autenticación
* Proveedores externos
* Validaciones personalizadas
* Lógicas de negocio específicas

---

## ✨ Beneficios Clave

* Arquitectura modular real (no monolítica)
* Core ligero y mantenible
* Escalabilidad horizontal por módulos
* Autorización avanzada basada en scopes
* Integración OAuth2 y OpenID Connect
* Extensible sin modificar el core
* Ideal para ecosistemas y plataformas

Este proyecto está pensado como la **base de un ecosistema**, no solo como una aplicación.

---

## 📚 Recursos

* [Documentación](https://gitlab.com/elyerr/oauth2-passport-server/-/wikis/home)
* [API Documentation](https://documenter.getpostman.com/view/5625104/2sB2xBDq6o)
* [Echo Server](https://gitlab.com/elyerr/echo-server) (coming soon)
* [Echo Client](https://gitlab.com/elyerr/echo-client-js) (coming soon)

---

## 🚢 Guías de Despliegue

* [English Documentation](./docs/deploy/deploy_en.md)
* [Spanish Documentation](./docs/deploy/deploy_es.md)

---

## 👨‍💻 Guías para Desarrolladores

* [English Documentation](./docs/dev/developers_en.md)
* [Spanish Documentation](./docs/deploy/deploy_es.md)

---

## 📄 Licencia

Este proyecto está licenciado bajo una
[Licencia de Uso No Comercial](LICENSE.md).

Para consultas comerciales, contacta al autor.

---

## 📬 Contacto

Para más información o soporte:

* Telegram: [https://t.me/elyerr](https://t.me/elyerr)
