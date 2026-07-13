# Instalar un módulo

Para instalar un nuevo módulo, ejecuta el siguiente comando desde la raíz del proyecto:

```bash
php artisan module:install
```

Este comando iniciará un asistente interactivo que te guiará durante todo el proceso de instalación.

## Seleccionar el proveedor

El primer paso consiste en seleccionar el proveedor desde el cual se obtendrá el módulo.

Actualmente están disponibles las siguientes opciones:

* **Git**: Compatible con cualquier repositorio Git, como GitHub, GitLab, Bitbucket o un servidor Git privado.
* **Packagist**: Permite instalar módulos publicados como paquetes Composer.

Utiliza las teclas **↑** y **↓** para seleccionar el proveedor y presiona **Enter** para continuar.

## Instalación desde un repositorio Git

Si seleccionas **Git**, deberás proporcionar la URL del repositorio.

Se admiten tanto repositorios públicos como privados utilizando HTTPS o SSH.

### Repositorio público (HTTPS)

```text
https://github.com/elyerrlabs/vpn.git
```

### Repositorio privado (SSH)

```text
git@github.com:elyerrlabs/vpn.git
```

### Configuración de SSH

Para utilizar repositorios privados mediante SSH, es necesario que el entorno donde se ejecuta OAuth2 Passport Server tenga configuradas las claves SSH correspondientes.

#### Entorno de desarrollo

Si utilizas el entorno **dev**, normalmente no será necesario realizar ninguna configuración adicional si las claves SSH ya existen en el host.

En sistemas Linux, Elymod utiliza por defecto el directorio:

```text
~/.ssh
```

Si utilizas una ubicación personalizada para tus claves, deberás montar ese directorio dentro del contenedor modificando el archivo `docker-dev.yml` y posteriormente reiniciar el contenedor.

#### Entorno de producción

En un entorno de producción puedes acceder al contenedor y generar un nuevo par de claves SSH si aún no existen.

Una vez generadas, puedes mostrar la clave pública con:

```bash
cat ~/.ssh/id_rsa.pub
```

o, si utilizas claves ED25519:

```bash
cat ~/.ssh/id_ed25519.pub
```

Copia la clave pública y agrégala a tu proveedor Git (GitHub, GitLab, Bitbucket, etc.) para habilitar el acceso al repositorio mediante SSH.

> **Recomendación:** Siempre que sea posible, utiliza SSH para acceder a repositorios privados. Es más seguro y evita tener que proporcionar credenciales durante la instalación.

## Instalación desde Packagist

Si seleccionas **Packagist**, simplemente introduce el nombre del paquete.

Por ejemplo:

```text
elyerr/vpn
```

## Seleccionar el entorno

El siguiente paso consiste en seleccionar el tipo de instalación.

Podrás elegir entre:

* **production**
* **dev**

Selecciona **production** para instalar únicamente las dependencias necesarias para producción.

Selecciona **dev** si vas a desarrollar el módulo o necesitas instalar también las dependencias de desarrollo.

## Repositorios privados mediante HTTPS

Si estás utilizando HTTPS, el asistente preguntará si el repositorio es privado.

* Responde **No** si el repositorio es público.
* Responde **Sí** si el repositorio es privado.

En el caso de un repositorio privado, se solicitarán los siguientes datos:

* Nombre de usuario.
* Personal Access Token (PAT).

Se recomienda generar un token con permisos únicamente de lectura (*read-only*).

Dependiendo de la terminal que utilices, puedes pegar el token mediante:

* **Ctrl + Shift + V**
* **Ctrl + Alt + V**

En algunos terminales el contenido no será visible mientras lo pegas. Esto es un comportamiento normal por motivos de seguridad.

Posteriormente se solicitará una **frase de cifrado (Encryption Key)**.

Esta clave se utilizará para cifrar el token almacenado y será requerida posteriormente cuando se realicen actualizaciones del módulo.

## Seleccionar la versión

Una vez validado el repositorio, Elymod mostrará todas las versiones disponibles.

Estas pueden corresponder a:

* Versiones estables (*tags*).
* Ramas (*branches*).

Utiliza las teclas **↑** y **↓** para seleccionar la versión que deseas instalar y presiona **Enter**.

### Instalación desde una rama

Si vas a desarrollar el módulo o deseas instalar una rama específica, selecciona la opción **Manual** e introduce el nombre de la rama.

Por ejemplo:

```text
develop
```

o

```text
feature/new-authentication
```

Esta opción resulta especialmente útil cuando varios desarrolladores trabajan simultáneamente sobre el mismo proyecto.

## Finalizar la instalación

Después de seleccionar la versión o rama, Elymod descargará el módulo, instalará sus dependencias, aplicará el aislamiento mediante **ElyScope** y dejará el módulo listo para utilizarse.

> **Consejo:** Para módulos privados se recomienda utilizar repositorios SSH en lugar de HTTPS. De esta forma evitarás introducir credenciales durante futuras instalaciones y actualizaciones, además de simplificar el flujo de trabajo.
