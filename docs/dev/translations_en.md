# Model Translations

The translation system allows any Eloquent model to support multiple languages with minimal configuration.

## 1. Implement the `Translatable` contract

The model must implement the `Translatable` contract.

```php
use App\Contracts\Translatable;

class User extends Model implements Translatable
{
    // ...
}
```

The contract defines two required methods:

```php
<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Translatable
{
    /**
     * Returns the attributes that support translations.
     *
     * Example:
     * ['title', 'description']
     */
    public function getTranslatableAttributes(): array;

    /**
     * Polymorphic relationship with translations.
     */
    public function translations(): MorphMany;
}
```

---

# 2. Add the `HasTranslation` trait

Include the trait in your model.

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

The trait automatically provides:

- The polymorphic `translations()` relationship.
- Translation attributes when converting the model to an array or JSON.

Example:

```json
{
    "about": "This is me",
    "about_es": "Este soy yo",
    "about_fr": "Je suis moi"
}
```

---

# 3. Create or update the model

Save the model as usual.

```php
$model = User::create([
    'name' => $data['name'],
    'about' => $data['about'],
]);
```

or

```php
$model = $this->userRepository->update($id, [
    "name" => $data['name'],
    "about" => $data['about'] ?? null,
]);
```

---

# 4. Synchronize translations

After creating or updating the model, synchronize its translations.

```php
syncTranslations($model, $data);
```

Where:

- `$model` must implement the `Translatable` contract.
- `$data` is the complete request payload as an array.

---

# Request format

The primary language is always stored in the model's original attribute.

Additional translations are submitted using the following format:

```php
[
    'about' => 'This is me',
    'about_es' => 'Este soy yo',
    'about_fr' => 'Je suis moi',
]
```

Where:

- `about` → Primary language.
- `about_es` → Spanish translation.
- `about_fr` → French translation.

The helper automatically detects every attribute matching the following pattern:

```text
attribute_locale
```

and stores each translation in the `translations` table.

---

# Result

When converting the model to an array or JSON, all translations are automatically appended.

Example:

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

# Recommendation: Use a Morph Map

If a module contains translatable models, it is **strongly recommended** to register a Morph Map alias in the module's `config/morph.php` file.

Example:

```php
return [

    "content_posts" => "\Content\App\Models\Post",

];
```

or

```php
return [

    "user_users" => "\Core\User\Model\User",

];
```

## Recommended naming convention

When developing modular applications, use the following format for morph aliases:

```text
module_name_table_name
```

Examples:

```text
content_posts
content_categories
user_users
user_roles
cms_pages
cms_menus
```

This naming convention helps prevent collisions between modules that may contain models with the same name.

For example, two different modules may both contain a `Post` model:

```text
Content\Post
Blog\Post
```

If both modules registered:

```php
'post'
```

a conflict would occur.

Instead, register unique aliases:

```php
return [

    "content_posts" => "\Content\App\Models\Post",
    "blog_posts" => "\Blog\App\Models\Post",

];
```

This guarantees that each morph alias remains unique and easy to identify.

## Why is this important?

Laravel stores the model type (`translatable_type`) inside the `translations` table.

Without a Morph Map, Laravel stores the fully qualified class name, for example:

```text
Content\App\Models\Post
```

This may become problematic if you later:

- reorganize your project structure;
- change a module namespace;
- rename a model;
- move a model to another directory.

In any of these cases, existing polymorphic relationships would break because the stored class name would no longer exist.

By using a Morph Map, Laravel stores only a stable identifier, such as:

```text
content_posts
```

This allows you to freely refactor your application's internal structure without affecting existing polymorphic relationships stored in the database.

> **Recommendation:** Always register a Morph Map for every model that participates in polymorphic relationships. In modular applications, use a combination of the module name and table name as the morph alias to guarantee unique identifiers and avoid future conflicts.

---

# Returning content in the current language

By default, the translation system preserves the original attribute and appends every available translation when the model is converted to an array or JSON.

Example:

```json
{
    "about": "This is me",
    "about_es": "Este soy yo",
    "about_fr": "Je suis moi"
}
```

This behavior is particularly useful for administration panels and editing forms, where access to both the original value and all available translations is required.

## Localizing the model

If you want to return only the content corresponding to the user's current language—for example, for posts, blogs, comments, pages, or any other public-facing content—you can simply call the `localize()` method.

```php
return $post->localize();
```

or

```php
return $user->localize();
```

The method automatically uses the application's current locale and replaces each original attribute with its translated value for that locale.

For example, if the current locale is **es**, the response becomes:

```json
{
    "about": "Este soy yo"
}
```

When using `localize()`:

- The original attribute name (`about`) is preserved.
- The attribute value is replaced with the translation matching the current locale.
- All additional translation attributes (`about_es`, `about_fr`, etc.) are removed from the response.
- The `translations` relationship is also excluded from the output.

As a result, API consumers receive only the content for the active language, producing a much cleaner response.

> **Note:** `localize()` is a convenience method provided by the trait. If your application requires a different localization strategy, you are free to implement your own logic.
