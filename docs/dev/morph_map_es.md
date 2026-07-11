# Relaciones Polimórficas (Morph Map)

Elymod utiliza **Morph Maps** de Laravel para resolver las relaciones polimórficas entre modelos.

En lugar de almacenar el nombre completo de una clase (FQCN) en la base de datos, Elymod almacena un identificador estable (tag o alias) que representa al modelo.

Este enfoque permite mantener las relaciones funcionando incluso cuando la estructura interna del proyecto cambia con el tiempo.

---

# ¿Qué problema resuelve?

Por defecto, Laravel almacena el nombre completo de la clase en los campos polimórficos.

Por ejemplo:

```text
Content\App\Models\Post
```

Si en el futuro ocurre alguno de estos cambios:

- reorganizas el proyecto;
- renombras un módulo;
- cambias el namespace;
- mueves un modelo a otra carpeta;
- refactorizas la estructura del proyecto;

Laravel ya no podrá resolver la relación porque la clase almacenada en la base de datos dejará de existir.

Esto obliga a realizar migraciones de datos únicamente por haber reorganizado el código fuente.

---

# La solución

En lugar de almacenar el nombre completo de la clase, Elymod utiliza un **Morph Map**, donde cada modelo posee un identificador estable.

Por ejemplo:

```text
content_posts
```

La base de datos únicamente almacena ese identificador.

Cuando Laravel necesita resolver la relación, consulta el Morph Map para conocer qué clase corresponde a ese alias.

De esta forma, aunque el modelo cambie de namespace o de ubicación dentro del proyecto, la relación seguirá funcionando mientras el alias permanezca igual.

---

# Archivo de configuración

Cada módulo dispone de un archivo:

```text
config/morph.php
```

Su estructura es la siguiente:

```php
<?php

return [

    // "module_table" => "\Module\App\Models\Model",

];
```

Por ejemplo:

```php
return [

    "content_posts" => "\Content\App\Models\Post",
    "content_categories" => "\Content\App\Models\Category",

];
```

o

```php
return [

    "user_users" => "\Core\User\Model\User",
    "user_roles" => "\Core\User\Model\Role",

];
```

---

# Convención recomendada

Para evitar conflictos entre módulos, se recomienda utilizar el siguiente formato:

```text
nombre_del_modulo_nombre_de_la_tabla
```

Ejemplos:

```text
content_posts
content_categories
blog_posts
blog_categories
user_users
user_roles
cms_pages
cms_menus
```

---

# ¿Por qué no usar únicamente el nombre del modelo?

Supongamos que tu aplicación instala dos módulos distintos.

**Módulo Content**

```php
Content\App\Models\Post
```

**Módulo Blog**

```php
Blog\App\Models\Post
```

Si ambos registran el mismo alias:

```php
'post'
```

se producirá una colisión, ya que Laravel únicamente permite un alias por modelo.

En cambio, utilizando el nombre del módulo como prefijo:

```php
return [

    "content_posts" => "\Content\App\Models\Post",
    "blog_posts" => "\Blog\App\Models\Post",

];
```

cada identificador será único.

---

# Ventajas

El uso de Morph Maps ofrece varias ventajas:

- Evita almacenar nombres completos de clases en la base de datos.
- Permite reorganizar el proyecto sin romper relaciones existentes.
- Facilita el mantenimiento de proyectos grandes y modulares.
- Evita colisiones entre módulos que utilizan modelos con el mismo nombre.
- Hace que los datos almacenados sean independientes de la estructura interna del código.

---

# Recomendaciones

Se recomienda registrar **todos** los modelos que participen en relaciones polimórficas dentro del archivo `config/morph.php` de su respectivo módulo.

Cada alias debe ser:

- único;
- estable;
- descriptivo;
- independiente del namespace del modelo.

Como regla general, utiliza siempre la siguiente convención:

```text
nombre_del_modulo_nombre_de_la_tabla
```

Esto garantiza compatibilidad futura, facilita la evolución del proyecto y evita conflictos cuando nuevos módulos sean instalados.

---

# Ejemplo completo

```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Polymorphic Model Map
    |--------------------------------------------------------------------------
    */

    "content_posts" => "\Content\App\Models\Post",
    "content_categories" => "\Content\App\Models\Category",

    "user_users" => "\Core\User\Model\User",
    "user_roles" => "\Core\User\Model\Role",

    "cms_pages" => "\Cms\App\Models\Page",
    "cms_menus" => "\Cms\App\Models\Menu",

];
```

Con esta configuración, la base de datos almacenará únicamente valores como:

```text
content_posts
user_users
cms_pages
```

en lugar de los nombres completos de las clases, haciendo que las relaciones polimórficas sean mucho más robustas frente a futuras refactorizaciones del proyecto.
