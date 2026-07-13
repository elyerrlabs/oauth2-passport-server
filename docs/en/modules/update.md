# Update a Module

Updating a module is a straightforward process. From the project root, run the following command:

```bash
php artisan module:update
```

## Select the Module

When you run the command, a list of all installed modules will be displayed.

Use the **↑** and **↓** arrow keys to select the module you want to update and press **Enter** to continue.

## Select the Version

Next, a list of all available versions for the module will be displayed.

Depending on the repository, you may find:

- Stable versions (_tags_).
- Development branches (_branches_).

This allows you, for example, to develop new features on a specific branch while testing changes in a **staging** environment, or to install a stable version for a **production** environment.

Select the version or branch you want to install and press **Enter**.

> **Recommendation:** For production environments, always use a stable version (_tag_). Development branches are intended for testing and continuous development.

## Reinstalling the Same Version

If you select the same version or branch that is already installed, the wizard will ask for confirmation to perform a reinstallation.

This is useful when you want to completely rebuild the module or reinstall its dependencies.

To continue, type:

```text
yes
```

If you do not wish to continue, type:

```text
no
```

## Select the Environment

The next step is to indicate the type of installation you want to perform.

You can choose between:

- **production**
- **dev**

Select **production** when the module is to be installed in a production or staging environment. In this mode, only production dependencies will be installed.

Select **dev** when you are developing the module. In this mode, development dependencies will also be installed, allowing you to run testing, analysis, and debugging tools.

## Confirm the Update

Before starting the update, the wizard will show a summary of the operation and request final confirmation.

Type:

```text
yes
```

to start the module update.

Or type:

```text
no
```

to cancel the process.

If you confirm the operation, Elymod will download and install the selected version, update the necessary dependencies, and leave the module ready for use.

> **Note:** If you answer **no** to any of the confirmations, the process will be canceled without making any changes to the installed module.
