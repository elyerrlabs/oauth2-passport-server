# Polymorphic Relationships (Morph Map)

Elymod uses Laravel's **Morph Maps** to resolve polymorphic relationships between models.

Instead of storing a model's fully qualified class name (FQCN) in the database, Elymod stores a stable identifier (tag or alias) that represents the model.

This approach allows your application to evolve over time without breaking existing polymorphic relationships.

---

# What problem does it solve?

By default, Laravel stores the fully qualified class name inside polymorphic relationship columns.

For example:

```text
Content\App\Models\Post
```

If you later:

- reorganize your project;
- rename a module;
- change a namespace;
- move a model to another directory;
- refactor your application's structure;

Laravel will no longer be able to resolve existing relationships because the stored class name no longer exists.

As a result, you would need to update your database records simply because your application's internal structure changed.

---

# The solution

Instead of storing the full class name, Elymod uses a **Morph Map**, where every model is identified by a stable alias.

For example:

```text
content_posts
```

Only this alias is stored in the database.

Whenever Laravel resolves a polymorphic relationship, it uses the Morph Map to determine which model class corresponds to that alias.

As long as the alias remains unchanged, your relationships will continue working even if the model is moved or renamed internally.

---

# Configuration file

Each module contains its own Morph Map configuration file:

```text
config/morph.php
```

Its structure is very simple:

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

# Recommended naming convention

To prevent naming conflicts between modules, Morph Map aliases should follow this convention:

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

# Why not use only the model name?

Imagine your application installs two different modules.

**Content Module**

```php
Content\App\Models\Post
```

**Blog Module**

```php
Blog\App\Models\Post
```

If both modules register the same alias:

```php
'post'
```

a collision will occur because Laravel only allows one alias for each morph identifier.

Instead, prefix the alias with the module name:

```php
return [

    "content_posts" => "\Content\App\Models\Post",
    "blog_posts" => "\Blog\App\Models\Post",

];
```

This guarantees that every alias is unique across the application.

---

# Benefits

Using Morph Maps provides several advantages:

- Avoids storing fully qualified class names in the database.
- Allows internal refactoring without breaking existing relationships.
- Simplifies maintenance of large modular applications.
- Prevents naming collisions between modules.
- Makes stored data independent from the application's internal structure.

---

# Recommendations

It is recommended to register **every model** participating in polymorphic relationships inside the module's `config/morph.php` file.

Each alias should be:

- unique;
- stable;
- descriptive;
- independent of the model namespace.

As a general rule, always use the following format:

```text
module_name_table_name
```

This convention ensures long-term compatibility, improves maintainability, and prevents conflicts when new modules are added to the application.

---

# Complete example

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

With this configuration, the database stores only values such as:

```text
content_posts
user_users
cms_pages
```

instead of fully qualified class names, making your polymorphic relationships significantly more resilient to future refactoring and module reorganization.
