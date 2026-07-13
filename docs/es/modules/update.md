# Actualizar un módulo

Actualizar un módulo es un proceso sencillo. Desde la raíz del proyecto ejecuta el siguiente comando:

```bash
php artisan module:update
```

## Seleccionar el módulo

Al ejecutar el comando, se mostrará una lista con todos los módulos instalados.

Utiliza las teclas **↑** y **↓** para seleccionar el módulo que deseas actualizar y presiona **Enter** para continuar.

## Seleccionar la versión

A continuación, se mostrará una lista con todas las versiones disponibles del módulo.

Dependiendo del repositorio, podrás encontrar:

- Versiones estables (_tags_).
- Ramas de desarrollo (_branches_).

Esto te permite, por ejemplo, desarrollar nuevas funcionalidades en una rama específica mientras pruebas los cambios en un entorno de **staging**, o instalar una versión estable para un entorno de **producción**.

Selecciona la versión o rama que deseas instalar y presiona **Enter**.

> **Recomendación:** Para entornos de producción utiliza siempre una versión estable (_tag_). Las ramas de desarrollo están pensadas para pruebas y desarrollo continuo.

## Reinstalación de la misma versión

Si seleccionas la misma versión o rama que ya está instalada, el asistente solicitará una confirmación para realizar una reinstalación.

Esto resulta útil cuando deseas reconstruir completamente el módulo o reinstalar sus dependencias.

Para continuar escribe:

```text
yes
```

Si no deseas continuar, escribe:

```text
no
```

## Seleccionar el entorno

El siguiente paso consiste en indicar el tipo de instalación que deseas realizar.

Podrás elegir entre:

- **production**
- **dev**

Selecciona **production** cuando el módulo vaya a instalarse en un entorno de producción o staging. En este modo únicamente se instalarán las dependencias necesarias para producción.

Selecciona **dev** cuando estés desarrollando el módulo. En este modo también se instalarán las dependencias de desarrollo, permitiendo ejecutar herramientas de pruebas, análisis y depuración.

## Confirmar la actualización

Antes de comenzar la actualización, el asistente mostrará un resumen de la operación y solicitará una confirmación final.

Escribe:

```text
yes
```

para iniciar la actualización del módulo.

O bien:

```text
no
```

para cancelar el proceso.

Si confirmas la operación, Elymod descargará e instalará la versión seleccionada, actualizará las dependencias necesarias y dejará el módulo listo para su uso.

> **Nota:** Si respondes **no** en cualquiera de las confirmaciones, el proceso se cancelará sin realizar ningún cambio en el módulo instalado.
