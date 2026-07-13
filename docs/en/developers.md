# 🧑‍💻 Development Guide (DEV)

This guide covers **only the development environment**, which is designed to provide a local, secure, and reproducible workflow.

The project runs **primarily inside Docker**, meaning that almost the entire runtime environment is isolated within containers.

You do **not** need to install the following on your operating system:

* Composer
* Node.js
* Nginx
* Supervisor
* Redis
* MariaDB/MySQL
* Any other service required by the application

The entire development stack runs inside Docker containers, ensuring that every developer works with the same environment.

---

## PHP on the Host

Although it is **not required**, having **PHP installed on your host machine** is recommended.

This is **not** necessary to run the project. Instead, it allows IDEs and code editors to provide a better development experience through features such as:

* Autocompletion
* Static analysis
* Class navigation
* Error detection
* Extensions such as **Intelephense**, **PHP Tools**, or the built-in tools available in **PhpStorm**

The application itself always runs inside Docker. Installing PHP locally only improves editor integration.

> **Goal:** Any developer should be able to clone the repository, start the environment with Docker, and begin developing within minutes, without complicated manual configuration.

---

# 🌱 Branches

The project maintains several branches, each serving a different purpose.

* **main** — Latest stable release. Intended for production environments.
* **staging** — Testing branch used to validate changes before they become part of a stable release.
* **dev** — Active development branch where new features and improvements are implemented.

## Which branch should I use?

The answer depends on what you plan to develop.

### Contributing to OAuth2 Passport Server

If you plan to contribute directly to the core project, work on the **dev** branch.

This is where all new features, fixes, and improvements are developed before they are released.

### Developing Elymod Modules

If your goal is to develop modules, **it is not recommended to work directly on the `dev` branch**.

Instead, use the same stable version that is running in your production environment, or at least a compatible release.

For example, if your server is running **v9.0.0**, create a development branch based on that version:

```bash
git checkout -b v9.0.0-dev v9.0.0
```

Or if your production environment is running **v8.0.1**:

```bash
git checkout -b v8.0.1-dev v8.0.1
```

This approach ensures that your modules are developed and tested against the same codebase that will run in production, avoiding compatibility issues caused by unreleased changes.

> **Recommendation:** Maintain a dedicated development branch based on the stable version you currently use. When a new release becomes available (for example, `v9.1.0`), review the release notes, update your environment, and create a new development branch from the corresponding tag.

---

# ✅ Prerequisites

The only required software on your machine is:

* Docker 24 or newer
* Docker Compose (official plugin)
* Git

Recommended development tools:

* Visual Studio Code or PhpStorm
* DBeaver or TablePlus (database client)

---

# 📦 Clone the Repository

```bash
git clone https://github.com/elyerrlabs/oauth2-passport-server.git
cd oauth2-passport-server
```

---

# ⚙️ Environment Configuration

## 1. Create the `.env` file

Copy the example configuration:

```bash
cp .env.example .env
```

A minimal development configuration looks like this:

```env
APP_ENV=dev
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8001
SCHEMA_HTTPS=http

# LOGS
LOG_CHANNEL=daily
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# DATABASE
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=oauth2
DB_USERNAME=admin
DB_PASSWORD=admin
```

> `DB_HOST=db` refers to the Docker service defined in `docker-dev.yml`.

> If `APP_KEY` is empty, it will be generated automatically during the first deployment.

---

# 🐳 Deploying the Development Environment

Starting the development environment is straightforward.

After completing the initial configuration, simply run:

```bash
./dev --deploy
```

This command prepares the entire development environment automatically and leaves the project ready for development.

## What does `./dev --deploy` do?

During deployment, the script automatically:

1. Detects your host **UID** and **GID** to avoid permission issues.
2. Validates the required variables in the `.env` file.
3. Stops existing containers if they are already running.
4. Builds or updates the Docker images.
5. Starts every required service using Docker Compose.
6. Installs project dependencies when necessary.
7. Starts all services managed by **Supervisor**.
8. Verifies that the main services are running correctly.

Once completed, the development environment is fully operational.

---

# Available Commands

The `dev` script centralizes every operation related to the development environment.

| Command             | Description                                                                          |
| ------------------- | ------------------------------------------------------------------------------------ |
| `./dev --deploy`    | Build and deploy the complete development environment.                               |
| `./dev --stop`      | Stop all development containers.                                                     |
| `./dev bash`        | Open a shell inside the development container using the configured development user. |
| `./dev --root bash` | Open a shell as **root** inside the container. Use only for administrative tasks.    |

---

# Running Commands

The `dev` script also allows you to execute commands directly inside the container without first opening an interactive shell.

Examples:

```bash
./dev php artisan migrate
```

```bash
./dev php artisan optimize:clear
```

```bash
./dev php artisan module:list
```

```bash
./dev composer install
```

```bash
./dev npm run dev
```

Although this is convenient for automation or one-off commands, it is generally recommended to work from an interactive shell during daily development.

Simply run:

```bash
./dev bash
```

Inside the container you can use `php`, `composer`, `artisan`, `npm`, and any other available tools without prefixing every command with `./dev`.

> **Note:** In most cases you will never need to interact directly with `docker compose`. The `./dev` script abstracts the entire environment and provides a unified interface for everyday development tasks.

---

# 🧩 System Configuration

OAuth2 Passport Server has been designed so that most configuration can be managed directly from the application without editing configuration files or modifying the source code.

From the administration panel you can configure:

* Redis
* CAPTCHA
* External services
* Queues
* Email
* Cache
* Sessions
* General system settings

This centralized approach allows the platform to be configured dynamically while keeping the application code untouched.

---

# Redis

Redis is one of the core components of the platform and can be used for several different features, depending on your configuration.

Typical use cases include:

* Cache
* Sessions
* Queues
* Horizon
* Any module requiring Redis

## Development Environment

The development environment already includes a Redis service running inside Docker.

No Redis installation is required on your host machine.

Configure Redis from:

**Settings → Redis**

Recommended configuration:

```text
Host: redis
Port: 6379
```

> **Important:** When running inside Docker, never use `127.0.0.1` or `localhost` as the Redis host. Those addresses point to the current container rather than the Redis service.
>
> Always use `redis`, which matches the Docker Compose service name.

---

# Queues

If you want Redis to process background jobs, navigate to:

**Settings → Queues**

and select:

```text
Driver: redis
```

Save the configuration and the application will begin using Redis as the queue backend.

When Redis is configured as the queue driver, **Laravel Horizon** automatically manages background job processing.

---

# Recommendations

* Always use the Redis service included with Docker during development.
* Configure the Redis host as `redis` instead of `localhost` or `127.0.0.1`.
* If you use Horizon, ensure the queue driver is set to `redis`.

Following these recommendations will keep your development environment consistent with production and avoid container networking issues.

---

# 🌐 Available Services

After deployment, the following services are available.

**Web Application**

```text
http://localhost:8001
```

**PostgreSQL**

```text
Host: 127.0.0.1
Port: 5435
Username: admin
Password: admin
```

---

# 🛠️ First Steps After Deployment

Once the development environment has been deployed successfully, the next step is to create a user account to access the administration panel.

## Create a User

Run the following command:

```bash
./dev php artisan settings:create-user
```

The setup wizard behaves differently depending on the configured environment.

## Development Mode (`ENV=dev`)

If your environment is configured as:

```text
ENV=dev
```

the wizard runs in development mode.

In this mode, you only need to select the type of user you want to create.

Use the **Up** and **Down** arrow keys to choose a user type and press **Enter**.

The system automatically generates:

* First name
* Last name
* Email address
* Password

Once the process finishes, the generated credentials are displayed so you can sign in immediately.

## Production or Staging Mode

If the environment is configured as **production** or **staging**, the wizard requests all information manually, including:

* First name
* Last name
* Email address
* Password
* User type

This behavior is intended for creating real accounts in non-development environments.

> **Note:** Development mode does not change the command itself—it simply reduces the amount of information required by the setup wizard.

---

# Useful Commands

## List all Artisan commands

```bash
./dev php artisan
```

## Create a user

```bash
./dev php artisan settings:create-user
```

## Install PHP dependencies

```bash
./dev composer install
```

> In most cases this is unnecessary because `./dev --deploy` installs all required dependencies automatically.

## Install JavaScript dependencies

```bash
./dev npm install
```

## Start the Vite development server

```bash
./dev npm run dev
```

## Build production assets

```bash
./dev npm run build
```

---

# Background Services

The development environment already includes all required background processes managed by **Supervisor**.

These include:

* Laravel Horizon
* Queue workers
* Laravel Scheduler

There is no need to start any of these services manually.

To verify their status, run:

```bash
./dev --root supervisorctl status
```

---

# Recommendations

* Do not execute `php`, `composer`, `npm`, or `artisan` directly on your host operating system.
* Always use the `./dev` wrapper or work from an interactive shell opened with `./dev bash`.
* Source code changes are reflected immediately through Docker bind mounts.
* Use the **DEV** environment exclusively for development and testing.

---

# Recommended Workflow

1. Clone the repository.
2. Configure the `.env` file.
3. Run `./dev --deploy`.
4. Create a user using `./dev php artisan settings:create-user`.
5. Sign in with the generated credentials.
6. Start developing.
