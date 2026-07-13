# Usuarios

El módulo de **Usuarios** permite administrar las cuentas registradas en el sistema y controlar su acceso mediante la asignación de **Scopes**.

Todas las operaciones relacionadas con los usuarios se encuentran protegidas por el modelo de autorización basado en **Grupos**, **Servicios** y **Roles**, permitiendo definir permisos de forma completamente granular.

---

# Funcionalidades

Actualmente el módulo de usuarios permite realizar las siguientes acciones:

- Registrar nuevos usuarios.
- Actualizar la información de un usuario.
- Gestionar los Scopes asignados.
- Habilitar usuarios.
- Deshabilitar usuarios.

Por el momento, la eliminación permanente de usuarios **no se encuentra disponible**.

Esta decisión busca preservar la integridad de la información y evitar la pérdida de datos referenciales dentro del sistema, ya que un usuario puede estar asociado a auditorías, registros históricos, recursos creados o información utilizada por otros módulos.

---

# Creación de usuarios

Cuando un usuario es registrado desde el panel de administración, el sistema genera automáticamente una **contraseña aleatoria y segura**.

Una vez creada la cuenta, el usuario recibirá un correo electrónico con sus credenciales de acceso e instrucciones para iniciar sesión.

Por este motivo, se recomienda configurar previamente el servicio de correo electrónico desde el panel de administración.

```text
Settings
└── Email
```

Si el servicio de correo no está configurado, el usuario será creado correctamente, pero no recibirá el correo con su contraseña inicial.

En este caso, el administrador deberá proporcionar las credenciales por otro medio o restablecer la contraseña posteriormente.

---

# Acceso al módulo

Existen dos formas de acceder a la administración de usuarios.

## Administrador del sistema

El administrador principal posee acceso completo de forma predeterminada.

No requiere la asignación de ningún Scope adicional.

---

## Mediante Scopes

También es posible delegar la administración de usuarios a otros miembros de la organización.

Para ello basta con asignar el Scope **Full** del servicio **Users** perteneciente al grupo **Administrator**.

```text
administrator_users_full
```

Este Scope concede acceso completo a todas las funcionalidades del módulo de usuarios.

---

# Control granular

El sistema permite otorgar únicamente las acciones necesarias para cada usuario.

Por ejemplo, el servicio **Users** podría disponer de los siguientes roles:

```text
Administrator
└── Users
    ├── Read
    ├── Create
    ├── Update
    ├── Manage Scope
    ├── Enable
    ├── Disable
    └── Full
```

Esto permite crear perfiles con distintos niveles de acceso.

Ejemplo:

```text
✓ administrator_users_read
✓ administrator_users_create
✗ administrator_users_manage_scope
✗ administrator_users_disable
✗ administrator_users_full
```

En este caso, el usuario podrá consultar y registrar nuevos usuarios, pero no podrá administrar sus permisos ni habilitar o deshabilitar cuentas.

---

# Scope Full

El Scope:

```text
administrator_users_full
```

otorga acceso completo al servicio **Users**.

Está pensado para administradores o responsables de la gestión de usuarios y evita tener que asignar cada Scope de forma individual.

Se recomienda utilizarlo únicamente cuando el usuario deba administrar completamente el módulo.

---

# Gestión de Scopes

Los permisos de un usuario pueden administrarse desde:

```text
Users
└── User Details
    └── Manage Scopes
```

Desde esta sección es posible buscar servicios y asignar o revocar los Scopes correspondientes.

Los cambios se aplican inmediatamente y determinan las funcionalidades a las que el usuario podrá acceder dentro de la plataforma.

---

# Recomendaciones

Se recomienda seguir siempre el principio de **mínimo privilegio**, asignando únicamente los Scopes estrictamente necesarios para desempeñar las funciones del usuario.

Esto mejora la seguridad del sistema, facilita la administración de permisos y reduce el riesgo de accesos no autorizados o modificaciones accidentales.
