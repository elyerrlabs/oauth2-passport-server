# Artisan Commands within a Module

Just like the main project (host), each module has its own Artisan console.

You can run commands from the module root using:

```bash  
php artisan
```

The main difference is that only commands compatible with the module environment will be available, including:

- `make:*` commands.
- Commands related to `api:response`.
- The module's own commands.

This allows you to develop a module almost independently of the main project.

---

# Custom Commands

You can create Artisan commands within the module the same way as in a Laravel application.

Once created, **manual registration is not required**. Elymod detects and registers them automatically during module boot.

## Naming Convention

To avoid conflicts between modules, all commands should follow this format:

```text
moduleName:module:command
```

If the command performs multiple actions on the same resource, it is recommended to add an action at the end:

```text
moduleName:module:command:action
```

### Examples

```text
elymod-app:module:client:create

elymod-app:module:client:update

elymod-app:module:client:disable

elymod-app:module:client:remove
```

This format avoids collisions between modules and makes it easy to identify the origin of each command.

---

# Scheduling Tasks

Modules can register scheduled tasks using Laravel's Scheduler.

To do so, simply define the tasks within the file:

```text
routes/console.php
```

For example:

```php
use Illuminate\Console\Scheduling\Schedule;

return function (Schedule $schedule) {

    $schedule->command('elymod-app:module:test-command')->everyMinute();

};
```

## Automatic Registration

Once you register your tasks in `routes/console.php`, **no additional configuration is required**.

During application boot, the host project automatically detects the `routes/console.php` files from all installed modules and registers their tasks with Laravel's Scheduler.

This means you only need to define the tasks you want to run; the rest of the process is completely automatic.

## Execution and Monitoring

OAuth2 Passport Server uses **Supervisor** to keep Laravel's Scheduler running.

There is no need to:

- Manually register commands.
- Create additional processes.
- Configure custom services.
- Implement logic to restart scheduled tasks.

If the Scheduler process stops unexpectedly for any reason (server restart, system errors, out of memory, etc.), **Supervisor will automatically restart it** as many times as necessary to restore the service.

As a module developer, you only need to worry about defining scheduled tasks.

The host project will automatically handle:

- Detecting tasks from all modules.
- Registering them with Laravel's Scheduler.
- Running them according to the defined schedule.
- Keeping the Scheduler running.
- Automatically restarting the process if any failure occurs.

Thanks to this mechanism, all modules share a single scheduled task infrastructure, simplifying development and eliminating the need for additional configuration.

---

# Middleware

Middleware works exactly the same as in Laravel.

You can create them using Artisan:

```bash
php artisan make:middleware Elymod
```

Once the middleware is created, implement the corresponding logic and register it in the file:

```text
app/Http/Kernel.php
```

For example:

```php
return [

    'elymod' => \Elymod\App\Http\Middleware\Elymod::class,

];
```

Then you can use it from any route in the module.

```php 
Route::middleware(['elymod'])->group(function () {

    // ...

});
```

or directly in a controller:

```php
$this->middleware('elymod');
```

---

# Translations

Translations follow exactly the same structure as the main project.

Simply create the `lang` folder inside the module and organize the files by language.

For example:

```text
lang/

├── en.json
│
├── es.json
│
└── fr.json
```

Then you can use Laravel's standard helper:

```php
__('messages.welcome');
```

or the helper available in JavaScript:

```javascript
__("messages.welcome");
```

No additional configuration is required. Elymod automatically detects the module's translation files and integrates them with the main project's internationalization system.

> **Note:** If your module uses the model translation system (`syncTranslations()`), the interface translations (`lang`) and content translations can be used together without additional configuration.