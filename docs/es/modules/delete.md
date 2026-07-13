# Eliminar un módulo

Para eliminar un módulo instalado, ejecuta el siguiente comando desde la raíz del proyecto:

```bash
php artisan module:delete
```

## Seleccionar el módulo

Al ejecutar el comando, se mostrará una lista con todos los módulos instalados.

Utiliza las teclas **↑** y **↓** para seleccionar el módulo que deseas eliminar y presiona **Enter** para continuar.

## Eliminar la configuración del módulo

A continuación, el asistente preguntará si también deseas eliminar la configuración almacenada por el módulo.

Esta configuración corresponde al archivo `settings.php`, ubicado en el directorio `env`, donde se almacenan los valores de configuración persistentes del módulo.

Responde:

- **yes** para eliminar la configuración del módulo.
- **no** para conservarla.

Conservar la configuración puede ser útil si planeas reinstalar el módulo posteriormente y deseas mantener su configuración anterior.

## Confirmar la eliminación

Antes de eliminar el módulo, el asistente solicitará una primera confirmación.

Escribe:

```text
yes
```

para continuar con el proceso o:

```text
no
```

para cancelarlo.

## Confirmación de eliminación de la configuración

Si en el paso anterior elegiste eliminar la configuración (`yes`), el asistente solicitará una segunda confirmación indicando que la configuración será eliminada de forma permanente.

Confirma la operación escribiendo nuevamente:

```text
yes
```

o cancélala escribiendo:

```text
no
```

## Finalizar el proceso

Una vez confirmadas las operaciones, Elymod eliminará el módulo del proyecto junto con todos sus archivos.

> **Importante:** Por motivos de seguridad, este comando **no elimina la base de datos del módulo**, ni sus tablas o la información almacenada en ellas.

La eliminación de la base de datos debe realizarse manualmente si realmente deseas borrar la información.

Esta decisión evita la pérdida accidental de datos y permite reinstalar el módulo posteriormente conservando la información existente si así lo deseas.

El comando elimina únicamente:

- El directorio del módulo.
- Sus dependencias.
- Archivos generados durante la instalación.
- La configuración persistente (solo si decidiste eliminarla).

La información almacenada en la base de datos permanecerá intacta hasta que sea eliminada manualmente.
