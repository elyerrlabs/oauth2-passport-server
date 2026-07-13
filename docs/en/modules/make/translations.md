# Model Translations

The translation system allows any Eloquent model to support multiple languages with minimal configuration.

## 1. Implement the `Translatable` Contract

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

# 2. Add the `HasTranslation` Trait

Include the trait in the model.

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

The trait automatically adds:

- The polymorphic `translations()` relationship.
- The translated attributes when converting the model to an array or JSON.

Example:

```json
{
  "about": "This is me",
  "about_es": "Este soy yo",
  "about_fr": "Je suis moi"
}
```

---

# 3. Create or Update the Model

Save the model normally.

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

# 4. Sync the Translations

After creating or updating the model, sync the translations.

```php
syncTranslations($model, $data);
```

Where:

- `$model` must implement the `Translatable` contract.
- `$data` corresponds to the complete array received from the request.

---

# Request Format

The primary language is always stored in the model's original attribute.

Additional translations are sent using the following format:

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

The helper automatically detects all attributes whose name follows the pattern:

```text
attribute_language
```

and registers each translation in the `translations` table.

---

# Result

When converting the model to an array or JSON response, translations are automatically added.

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

## Recommendation: Use Morph Map

When a module implements models with translation support, it is **highly recommended** to register a polymorphic alias (Morph Map) in its `config/morph.php` configuration file.

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

### Recommended Convention

If you are developing a module, it is recommended that the alias follows this format:

```text
module_name_table_name
```

For example:

```text
content_posts
content_categories
user_users
user_roles
cms_pages
cms_menus
```

This convention helps avoid collisions when different modules use similar model or table names.

For example, two different modules might have a model called `Post`:

```text
Content\Post
Blog\Post
```

If both simply registered:

```php
'post'
```

it would cause a conflict in the Morph Map.

Instead, using the module prefix:

```php
return [

    "content_posts" => "\Content\App\Models\Post",
    "blog_posts" => "\Blog\App\Models\Post",

];
```

each alias remains unique and easy to identify.

### Why is it important?

Laravel stores the model type (`translatable_type`) in the `translations` table.

If a Morph Map is not used, Laravel will store the full class name, for example:

```text
Content\App\Models\Post
```

This can cause problems if in the future you:

- reorganize the project structure;
- change a module's namespace;
- rename a class;
- move a model to another folder.

In any of these cases, polymorphic relationships will stop working because the class name stored in the database will no longer exist.

When using a Morph Map, only a stable identifier is stored, for example:

```text
content_posts
```

Therefore, even if the internal project structure changes, relationships will continue to work without needing to modify existing database records.

> **Recommendation:** in modular projects, always register a Morph Map for each model that uses polymorphic relationships and use an alias composed of the module name and table name. This practice ensures unique identifiers, makes project maintenance easier, and avoids conflicts between present or future modules.

---

# Display Content in the Current Language

By default, the system keeps the original attribute and adds all available translations when converting the model to an array or JSON.

Example:

```json
{
  "about": "This is me",
  "about_es": "Este soy yo",
  "about_fr": "Je suis moi"
}
```

This behavior is useful for admin panels or edit forms, as it allows simultaneous access to the original content and all its translations.

## Localize the Model

If you want to display only the content corresponding to the user's current language (for example, in posts, blogs, comments, pages, or any public content), you can use the `localize()` method.

```php
return $post->localize();
```

or

```php
return $user->localize();
```

The method will use the application's current language and automatically replace the original attribute's value with its corresponding translation.

For example, if the current language is **es**, the result will be:

```json
{
  "about": "Este soy yo"
}
```

In this mode:

- The original attribute keeps its name (`about`), but its value corresponds to the current language.
- All additional translations (`about_es`, `about_fr`, etc.) are removed from the response.
- The `translations` relationship will also not be included in the output.

This way, the API consumer only receives the content corresponding to the requested language.

> **Note:** `localize()` is a utility provided by the trait to facilitate generating localized responses. However, each project is free to implement its own localization strategy if different behavior is needed.
