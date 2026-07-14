# Traducciones de Modelos

El sistema de traducciones permite que cualquier modelo de Eloquent soporte múltiples idiomas con una configuración mínima.

## 1. Implementar el contrato `Translatable`

El modelo debe implementar el contrato `Translatable`.

```php
use App\Contracts\Translatable;

class User extends Model implements Translatable
{
    // ...
}
```

El contrato define dos métodos obligatorios:

```php
<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Translatable
{
    /**
     * Devuelve los atributos que soportan traducciones.
     *
     * Ejemplo:
     * ['title', 'description']
     */
    public function getTranslatableAttributes(): array;

    /**
     * Relación polimórfica con las traducciones.
     */
    public function translations(): MorphMany;

    /**
     * Set morph class identifier
     * @return string
     */
    public function getMorphClassIdentifier(): string;
}
```

---

# 2. Agregar el trait `HasTranslation`

Incluye el trait en el modelo.

```php
use App\Support\HasTranslation;

class User extends Model implements Translatable
{
    use HasTranslation;

    public function getTranslatableAttributes(): array
    {
        return [
            'about',
        ];
    }
}
```

El trait agrega automáticamente:

- La relación polimórfica `translations()`.
- Los atributos traducidos al convertir el modelo en un arreglo o JSON.

Ejemplo:

```json
{
    "about": "This is me",
    "about_es": "Este soy yo",
    "about_fr": "Je suis moi"
}
```

---

# 3. Crear o actualizar el modelo

Guarda el modelo normalmente.

```php
$model = User::create([
    'name' => $data['name'],
    'about' => $data['about'],
]);
```

o

```php
$model = $this->userRepository->update($id, [
    "name" => $data['name'],
    "about" => $data['about'] ?? null,
]);
```

---

# 4. Sincronizar las traducciones

Después de crear o actualizar el modelo, sincroniza las traducciones.

```php
syncTranslations($model, $data);
```

Donde:

- `$model` debe implementar el contrato `Translatable`.
- `$data` corresponde al arreglo completo recibido desde la petición.

---

## Extraer campos traducibles

Si deseas personalizar las traducciones o generar campos calculados que no provienen directamente de la entrada del usuario, puedes utilizar la función:

```php
extractTranslationsFields(
    Translatable $translatable,
    array $inputs,
    bool $langs = false
)
```

Esta función extrae todos los campos traducibles definidos por el modelo que implementa la interfaz `Translatable`.

### Parámetros

- **`$translatable`**: Instancia del modelo que implementa la interfaz `Translatable`.
- **`$inputs`**: Arreglo con los datos de entrada, normalmente obtenido del request.
- **`$langs`** _(opcional)_: Por defecto es `false`. Si se establece en `true`, la función agregará una clave adicional llamada **`langs`**, la cual contendrá un arreglo con todos los idiomas detectados en los campos traducibles.

### Ejemplo

```php
$translations = extractTranslationsFields(
    $page,
    $request->all(),
    true
);
```

El resultado incluirá todos los campos traducibles encontrados y, si `$langs` es `true`, también devolverá:

```php
[
    'langs' => ['es', 'fr'],
]
```

### Casos de uso

Esta función resulta especialmente útil cuando necesitas generar valores traducidos de forma automática para campos que **no son enviados desde el formulario**, sino que son calculados por la aplicación.

Por ejemplo, puedes utilizarla para generar las traducciones de campos como:

- `slug`
- `path`
- Metadatos SEO
- Cualquier otro atributo personalizado

Gracias al arreglo `langs`, podrás conocer qué idiomas fueron enviados en la solicitud y generar automáticamente la variante correspondiente para cada uno antes de llamar a `syncTranslations()`.

---

## Regla de validación `UniqueTranslation`

La regla `UniqueTranslation` valida la unicidad de **todos los campos traducidos** definidos por un modelo que implemente la interfaz `Translatable`.

A diferencia de una regla de validación tradicional, solo es necesario registrarla **una vez**. La regla detecta automáticamente todos los campos traducibles enviados en la solicitud y verifica que sus valores no existan previamente en la tabla de traducciones.

### Uso básico

```php
use App\Rules\UniqueTranslation;
use Content\App\Models\Page;

$this->validate($request, [
    'name' => [
        'required',
        new UniqueTranslation(new Page()),
    ],
]);
```

En este ejemplo, la regla se aplica únicamente al campo `name`, pero validará automáticamente todos los campos traducibles definidos por el modelo.

Si el modelo define:

```php
public function getTranslatableAttributes(): array
{
    return [
        'name',
        'slug',
        'path',
    ];
}
```

Y la solicitud contiene:

```php
[
    'name' => 'Home',
    'name_es' => 'Inicio',
    'name_fr' => 'Accueil',
    'slug_es' => 'inicio',
    'path_fr' => 'accueil',
]
```

Todos los campos traducidos serán validados automáticamente.

### Errores de validación

Si se detectan traducciones duplicadas, la validación fallará y mostrará un mensaje similar a:

```text
Los siguientes campos de traducción ya están en uso: name_es, slug_fr.
```

El mensaje se asociará al campo donde fue registrada la regla (generalmente el campo principal, como `name`).

### Validaciones personalizadas

Si tu proyecto requiere un comportamiento diferente, por ejemplo mostrar el error debajo de cada campo traducido, puedes crear tu propia regla de validación o implementar una validación personalizada utilizando las herramientas de Laravel, como `Validator::after()`.

`UniqueTranslation` está diseñada para ofrecer una solución simple y automática para la mayoría de los casos de uso, reduciendo la configuración necesaria y evitando registrar una regla para cada idioma o atributo traducible.

---

# Formato de la solicitud

El idioma principal siempre se almacena en el atributo original del modelo.

Las traducciones adicionales se envían utilizando el siguiente formato:

```php
[
    'about' => 'This is me',
    'about_es' => 'Este soy yo',
    'about_fr' => 'Je suis moi',
]
```

Donde:

- `about` → Idioma principal.
- `about_es` → Traducción al español.
- `about_fr` → Traducción al francés.

El helper detecta automáticamente todos los atributos cuyo nombre siga el patrón:

```text
atributo_idioma
```

y registra cada traducción en la tabla `translations`.

---

# Resultado

Al convertir el modelo en un arreglo o respuesta JSON, las traducciones se agregan automáticamente.

Ejemplo:

```json
{
    "id": 1,
    "name": "John Doe",
    "about": "This is me",
    "about_es": "Este soy yo",
    "about_fr": "Je suis moi"
}
```

---

## Recomendación: utilizar Morph Map

Cuando un módulo implemente modelos con soporte para traducciones, es **altamente recomendable** registrar un alias polimórfico (Morph Map) en su archivo de configuración `config/morph.php`.

Ejemplo:

```php
return [

    "content_posts" => \Content\App\Models\Post::class,

];
```

o

```php
return [

    "user_users" => \Core\User\Model\User::class,

];
```

### Convención recomendada

Si estás desarrollando un módulo, se recomienda que el alias siga el siguiente formato:

```text
nombre_del_modulo_nombre_de_la_tabla
```

Por ejemplo:

```text
content_posts
content_categories
user_users
user_roles
cms_pages
cms_menus
```

Esta convención ayuda a evitar colisiones cuando diferentes módulos utilizan nombres de modelos o tablas similares.

Por ejemplo, dos módulos distintos podrían tener un modelo llamado `Post`:

```text
Content\Post
Blog\Post
```

Si ambos registraran simplemente:

```php
'post'
```

se produciría un conflicto en el Morph Map.

En cambio, utilizando el prefijo del módulo:

```php
return [

    "content_posts" => \Content\App\Models\Post::class,
    "blog_posts" => \Blog\App\Models\Post::class,

];
```

cada alias permanece único y fácil de identificar.

### ¿Por qué es importante?

Laravel almacena el tipo del modelo (`translatable_type`) en la tabla `translations`.

Si no se utiliza un Morph Map, Laravel almacenará el nombre completo de la clase, por ejemplo:

```text
Content\App\Models\Post
```

Esto puede ocasionar problemas si en el futuro:

- reorganizas la estructura del proyecto;
- cambias el namespace de un módulo;
- renombras una clase;
- mueves un modelo a otra carpeta.

En cualquiera de estos casos, las relaciones polimórficas dejarán de funcionar porque el nombre de la clase almacenado en la base de datos ya no existirá.

Al utilizar un Morph Map únicamente se almacena un identificador estable, por ejemplo:

```text
content_posts
```

Por ello, aunque la estructura interna del proyecto cambie, las relaciones seguirán funcionando sin necesidad de modificar los registros existentes en la base de datos.

> **Recomendación:** en proyectos modulares, registra siempre un Morph Map para cada modelo que utilice relaciones polimórficas y utiliza un alias compuesto por el nombre del módulo y el nombre de la tabla. Esta práctica garantiza identificadores únicos, facilita el mantenimiento del proyecto y evita conflictos entre módulos presentes o futuros.

---

# Mostrar el contenido en el idioma actual

Por defecto, el sistema conserva el atributo original y agrega todas las traducciones disponibles al convertir el modelo en un arreglo o JSON.

Ejemplo:

```json
{
    "about": "This is me",
    "about_es": "Este soy yo",
    "about_fr": "Je suis moi"
}
```

Este comportamiento resulta útil para paneles de administración o formularios de edición, ya que permite acceder simultáneamente al contenido original y a todas sus traducciones.

## Localizar el modelo

Si deseas mostrar únicamente el contenido correspondiente al idioma actual del usuario (por ejemplo en publicaciones, blogs, comentarios, páginas o cualquier contenido público), puedes utilizar el método `localize()`.

```php
return $post->localize();
```

o

```php
return $user->localize();
```

El método utilizará el idioma actual de la aplicación y reemplazará automáticamente el valor del atributo original por su traducción correspondiente.

Por ejemplo, si el idioma actual es **es**, el resultado será:

```json
{
    "about": "Este soy yo"
}
```

En este modo:

- El atributo original conserva el nombre (`about`), pero su valor corresponde al idioma actual.
- Todas las traducciones adicionales (`about_es`, `about_fr`, etc.) son eliminadas de la respuesta.
- La relación `translations` tampoco será incluida en la salida.

De esta forma, el consumidor de la API únicamente recibe el contenido correspondiente al idioma solicitado.

> **Nota:** `localize()` es una utilidad proporcionada por el trait para facilitar la generación de respuestas localizadas. Sin embargo, cada proyecto es libre de implementar su propia estrategia de localización si necesita un comportamiento diferente.
