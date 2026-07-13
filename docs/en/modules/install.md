# Install a Module

To install a new module, run the following command from the project root:

```bash
php artisan module:install
```

This command will start an interactive wizard that will guide you through the entire installation process.

## Select the Provider

The first step is to select the provider from which the module will be obtained.

The following options are currently available:

- **Git**: Compatible with any Git repository, such as GitHub, GitLab, Bitbucket, or a private Git server.
- **Packagist**: Allows you to install modules published as Composer packages.

Use the **↑** and **↓** arrow keys to select the provider and press **Enter** to continue.

## Installation from a Git Repository

If you select **Git**, you will need to provide the repository URL.

Both public and private repositories are supported using HTTPS or SSH.

### Public Repository (HTTPS)

```text
https://github.com/elyerrlabs/vpn.git
```

### Private Repository (SSH)

```text
git@github.com:elyerrlabs/vpn.git
```

### SSH Configuration

To use private repositories via SSH, the environment where OAuth2 Passport Server is running must have the corresponding SSH keys configured.

#### Development Environment

If you are using the **dev** environment, no additional configuration will normally be required if the SSH keys already exist on the host.

On Linux systems, Elymod uses the following directory by default:

```text
~/.ssh
```

If you use a custom location for your keys, you will need to mount that directory inside the container by modifying the `docker-dev.yml` file and then restarting the container.

#### Production Environment

In a production environment, you can access the container and generate a new SSH key pair if they don't already exist.

Once generated, you can display the public key with:

```bash
cat ~/.ssh/id_rsa.pub
```

or, if you are using ED25519 keys:

```bash
cat ~/.ssh/id_ed25519.pub
```

Copy the public key and add it to your Git provider (GitHub, GitLab, Bitbucket, etc.) to enable access to the repository via SSH.

> **Recommendation:** Whenever possible, use SSH to access private repositories. It is more secure and avoids having to provide credentials during installation.

## Installation from Packagist

If you select **Packagist**, simply enter the package name.

For example:

```text
elyerr/vpn
```

## Select the Environment

The next step is to select the type of installation.

You can choose between:

- **production**
- **dev**

Select **production** to install only the dependencies needed for production.

Select **dev** if you are going to develop the module or need to install development dependencies as well.

## Private Repositories via HTTPS

If you are using HTTPS, the wizard will ask if the repository is private.

- Answer **No** if the repository is public.
- Answer **Yes** if the repository is private.

In the case of a private repository, the following information will be requested:

- Username.
- Personal Access Token (PAT).

It is recommended to generate a token with read-only permissions.

Depending on your terminal, you can paste the token using:

- **Ctrl + Shift + V**
- **Ctrl + Alt + V**

On some terminals, the content will not be visible while you paste it. This is normal behavior for security reasons.

An **Encryption Key** will then be requested.

This key will be used to encrypt the stored token and will be required later when performing module updates.

## Select the Version

Once the repository is validated, Elymod will display all available versions.

These may correspond to:

- Stable versions (_tags_).
- Branches (_branches_).

Use the **↑** and **↓** arrow keys to select the version you want to install and press **Enter**.

### Installation from a Branch

If you are going to develop the module or want to install a specific branch, select the **Manual** option and enter the branch name.

For example:

```text
develop
```

or

```text
feature/new-authentication
```

This option is especially useful when multiple developers are working simultaneously on the same project.

## Complete the Installation

After selecting the version or branch, Elymod will download the module, install its dependencies, apply isolation via **ElyScope**, and leave the module ready to use.

> **Tip:** For private modules, it is recommended to use SSH repositories instead of HTTPS. This way, you will avoid entering credentials during future installations and updates, as well as simplifying the workflow.
