# Clientes OAuth2 y OpenID Connect

OAuth2 Passport Server permite registrar clientes compatibles con **OAuth2** y **OpenID Connect (OIDC)** para proteger aplicaciones, APIs y servicios externos.

El sistema distingue dos tipos de clientes según su propósito y el nivel de confianza de la aplicación.

---

# Tipos de clientes

Existen dos áreas para registrar clientes.

## Developer

La sección **Developer** está destinada a que los desarrolladores registren sus propias aplicaciones.

Estos clientes están pensados para:

- Aplicaciones web.
- Aplicaciones móviles.
- APIs.
- Integraciones de terceros.
- Herramientas de desarrollo.

Cada desarrollador únicamente podrá administrar los clientes que le pertenecen.

---

## Administrator

La sección **Administrator** está destinada al registro de aplicaciones oficiales de la organización.

Estas aplicaciones son consideradas **aplicaciones de confianza** y normalmente corresponden a sistemas internos como:

- Panel de administración.
- Portal de empleados.
- Intranet.
- Sistemas corporativos.
- Aplicaciones propias de la empresa.

Los clientes registrados desde esta sección pueden utilizar funcionalidades exclusivas para aplicaciones internas.

---

# Prompts soportados

OAuth2 Passport Server soporta los prompts definidos por OpenID Connect.

- `consent`
- `login`
- `none`

Además, incorpora un prompt adicional:

```text
internal
```

Este prompt únicamente está disponible para clientes registrados desde la sección **Administrator**.

Su propósito es identificar aplicaciones internas de la organización.

Cuando un cliente utiliza el prompt **internal**, el servidor omite la pantalla de consentimiento del usuario, ya que la aplicación ha sido previamente marcada como confiable.

Esto mejora la experiencia de uso en aplicaciones oficiales donde no tiene sentido solicitar autorización cada vez que un usuario inicia sesión.

---

# Descarga de credenciales

Al registrar un nuevo cliente, el sistema permite descargar un archivo con todas las credenciales generadas.

Este archivo contiene la información necesaria para integrar la aplicación con el servidor de autorización utilizando OAuth2 u OpenID Connect.

Generalmente incluye datos como:

- Client ID
- Client Secret
- Redirect URIs
- Endpoints del servidor
- Configuración básica del cliente

Se recomienda almacenar este archivo en un lugar seguro.

---

# Clientes de acceso personal

Los **Personal Access Tokens** permiten que un usuario genere tokens API desde su cuenta.

Antes de que un usuario pueda crear este tipo de tokens, es necesario registrar un **Personal Access Client**.

Este proceso únicamente puede realizarlo un administrador.

---

## Crear un Personal Access Client

Desde el panel de administración seleccione:

```text
Administrator
└── OAuth Clients
    └── Create Personal Access Client
```

Asigne un nombre descriptivo al cliente y complete el proceso de registro.

Una vez creado, todos los usuarios autorizados podrán generar sus propios tokens de acceso personal.

---

# Generación de API Keys

Los usuarios pertenecientes al grupo **Developer** podrán generar tokens API desde la sección:

```text
Developer
└── API Keys
```

Estos tokens pueden utilizarse para consumir APIs protegidas por OAuth2 sin necesidad de realizar el flujo completo de autorización en cada solicitud.

Esta funcionalidad resulta especialmente útil para:

- Scripts.
- Automatizaciones.
- Integraciones entre sistemas.
- Herramientas CLI.
- Aplicaciones backend.

---

# Recomendaciones

- Registre aplicaciones oficiales únicamente desde la sección **Administrator**.
- Utilice el prompt `internal` únicamente para aplicaciones completamente confiables.
- Nunca comparta el **Client Secret** públicamente.
- Guarde el archivo de credenciales en un lugar seguro.
- Cree al menos un **Personal Access Client** antes de habilitar la generación de API Keys para los desarrolladores.

Siguiendo estas recomendaciones, podrá administrar de forma segura tanto las aplicaciones internas de la organización como las integraciones desarrolladas por terceros.