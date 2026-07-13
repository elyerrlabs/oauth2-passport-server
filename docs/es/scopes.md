# Grupos

Los **Grupos (Groups)** representan el nivel más alto dentro del modelo de autorización. Su principal objetivo es **clasificar y delimitar los servicios** según el contexto o área de la organización.

Un grupo no otorga permisos por sí mismo. En su lugar, actúa como un contenedor lógico donde se organizan los servicios.

```text
Grupo
└── Servicio
```

---

## ¿Por qué existen los grupos?

En organizaciones grandes es común que un mismo servicio tenga distintos comportamientos dependiendo del área o nivel de responsabilidad del usuario.

Por ejemplo, un servicio llamado **VPN** puede ser utilizado tanto por administradores como por desarrolladores, pero las acciones permitidas serán diferentes.

```text
Administrator
└── VPN

Developer
└── VPN
```

Aunque ambos utilizan el servicio **VPN**, pertenecen a grupos distintos y representan contextos completamente diferentes.

Gracias a esta separación es posible reutilizar nombres de servicios sin generar conflictos.

Por ejemplo, esto es completamente válido:

```text
Administrator
└── VPN

Developer
└── VPN

Support
└── VPN
```

Sin embargo, dentro de un mismo grupo no pueden existir dos servicios con el mismo nombre.

```text
Administrator
├── VPN
└── VPN   ✗ No permitido
```

Esto garantiza que cada servicio tenga un identificador único dentro de su grupo.

---

# Servicios

Un **Servicio (Service)** representa una funcionalidad, módulo o área específica del sistema.

Describe **qué hace** una parte del sistema, pero no **qué acciones** puede realizar un usuario sobre ella.

Ejemplos de servicios:

- VPN
- Ecommerce
- CMS
- Billing
- Inventory
- Reports
- Users
- Dashboard

Un servicio siempre pertenece a un grupo.

```text
Administrator
├── VPN
├── Users
└── Dashboard

Developer
├── VPN
└── Reports

Customer
└── Ecommerce
```

Esto permite que un mismo servicio tenga diferentes responsabilidades dependiendo del grupo al que pertenece.

Por ejemplo:

```text
Administrator
└── VPN
    ├── Administrar servidores
    ├── Crear usuarios
    └── Eliminar configuraciones

Developer
└── VPN
    ├── Ver servidores
    ├── Descargar configuraciones
    └── Revisar logs
```

Aunque ambos servicios se llaman **VPN**, representan contextos completamente distintos.

---

# Roles

Los **Roles** representan las acciones que pueden realizarse sobre un servicio.

Mientras que el servicio define **sobre qué recurso** se trabaja, el rol define **qué operación está permitida**.

Ejemplos:

- Read
- Write
- Create
- Update
- Delete
- Execute
- Export
- Import

Los roles tampoco son estáticos.

Cada servicio puede definir únicamente las acciones que necesite.

Por ejemplo:

```text
VPN
├── Read
├── Create
├── Update
└── Delete
```

Mientras que otro servicio podría tener:

```text
Reports
├── Read
├── Export
└── Share
```

---

# Scope

Cuando se combinan un **Grupo**, un **Servicio** y un **Rol**, se genera un **Scope**.

Un Scope representa el nivel más pequeño de autorización dentro del sistema.

```text
Group  + Service + Role  = Scope
```

Ejemplo:

```text
Administrator + VPN + Read = administrator_vpn_read
```

Otro ejemplo:

```text
Developer + VPN  + Read = developer_vpn_read
```

Cada Scope posee un identificador interno llamado **`gsr_id`**, el cual es único e irrepetible dentro del sistema.

Este identificador permite referenciar el Scope de forma segura sin depender de su nombre.

---

# Modelo de autorización

```text
                    Group
                      │
        ┌─────────────┴─────────────┐
        │                           │
 Administrator                Developer
        │                           │
        │                           │
       VPN                         VPN
        │                           │
  ┌─────┴─────┐              ┌──────┴──────┐
  │           │              │             │
 Read      Write          Read         Deploy
  │           │              │             │
  └─────┬─────┘              └──────┬──────┘
        │                           │
 administrator_vpn_read     developer_vpn_read
```

---

# Asignación de Scopes

Los Scopes no aparecen automáticamente para ser asignados a los usuarios.

Primero es necesario definir:

1. El Grupo.
2. El Servicio.
3. Los Roles del servicio.

Una vez que un servicio posee uno o más roles, el sistema genera automáticamente los Scopes correspondientes.

Estos estarán disponibles desde el panel de administración.

```text
Usuarios
└── Detalles
    └── Manage Scopes
```

Desde esta sección el administrador puede buscar un servicio y asignar únicamente los Scopes necesarios para un usuario.

Por ejemplo:

```text
✓ administrator_vpn_read
✓ administrator_vpn_write
✗ administrator_vpn_delete
```

En este caso, el usuario podrá consultar y modificar información relacionada con el servicio VPN, pero no tendrá permisos para eliminar recursos.

---

# Ventajas de este modelo

A diferencia de un sistema tradicional basado únicamente en permisos, este modelo organiza la autorización en tres niveles claramente definidos.

- **Grupo:** delimita el contexto o área organizacional.
- **Servicio:** representa una funcionalidad o módulo del sistema.
- **Rol:** define la acción permitida sobre ese servicio.

La combinación de estos tres elementos genera un Scope único, permitiendo un control extremadamente granular sobre cualquier funcionalidad de la plataforma.

Este enfoque facilita la reutilización de servicios, evita conflictos de nombres y permite construir modelos de autorización complejos sin perder claridad en la administración de permisos.
