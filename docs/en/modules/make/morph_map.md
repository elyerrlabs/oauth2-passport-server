# Polymorphic Relationships (Morph Map)

Elymod uses Laravel's **Morph Maps** to resolve polymorphic relationships between models.

Instead of storing the full class name (FQCN) in the database, Elymod stores a stable identifier (tag or alias) that represents the model.

This approach keeps relationships working even when the internal project structure changes over time.

---

# What problem does it solve?

By default, Laravel stores the full class name in polymorphic fields.

For example:

```text
Content\App\Models\Post
```

If any of these changes occur in the future:

- you reorganize the project;
- you rename a module;
- you change the namespace;
- you move a model to another folder;
- you refactor the project structure;

Laravel will no longer be able to resolve the relationship because the class stored in the database will no longer exist.

This forces you to run data migrations just because you reorganized the source code.

---

# The solution

Instead of storing the full class name, Elymod uses a **Morph Map**, where each model has a stable identifier.

For example:

```text
content_posts
```

The database only stores that identifier.

When Laravel needs to resolve the relationship, it queries the Morph Map to find out which class corresponds to that alias.

This way, even if the model changes namespace or location within the project, the relationship will continue to work as long as the alias remains the same.

---

# Configuration File

Each module has a configuration file:

```text
config/morph.php
```

Its structure is as follows:

```php
<?php

return [

    // "module_table" => "\Module\App\Models\Model",

];
```

For example:

```php
return [

    "content_posts" => "\Content\App\Models\Post",
    "content_categories" => "\Content\App\Models\Category",

];
```

or

```php
return [

    "user_users" => "\Core\User\Model\User",
    "user_roles" => "\Core\User\Model\Role",

];
```

---

# Recommended Convention

To avoid conflicts between modules, it is recommended to use the following format:

```text
module_name_table_name
```

Examples:

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

# Why not just use the model name?

Suppose your application installs two different modules.

**Content Module**

```php
Content\App\Models\Post
```

**Blog Module**

```php
Blog\App\Models\Post
```

If both register the same alias:

```php
'post'
```

a collision will occur, since Laravel only allows one alias per model.

Instead, using the module name as a prefix:

```php
return [

    "content_posts" => "\Content\App\Models\Post",
    "blog_posts" => "\Blog\App\Models\Post",

];
```

each identifier remains unique.

---

# Advantages

Using Morph Maps offers several advantages:

- Avoids storing full class names in the database.
- Allows reorganizing the project without breaking existing relationships.
- Makes maintenance of large, modular projects easier.
- Prevents collisions between modules that use models with the same name.
- Makes stored data independent of the internal code structure.

---

# Recommendations

It is recommended to register **all** models that participate in polymorphic relationships within the `config/morph.php` file of their respective module.

Each alias should be:

- unique;
- stable;
- descriptive;
- independent of the model's namespace.

As a general rule, always use the following convention:

```text
module_name_table_name
```

This ensures future compatibility, makes project evolution easier, and prevents conflicts when new modules are installed.

---

# Complete Example

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

With this configuration, the database will only store values like:

```text
content_posts
user_users
cms_pages
```

instead of full class names, making polymorphic relationships much more robust against future project refactorings.
