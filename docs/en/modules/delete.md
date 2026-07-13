# Delete a Module

To delete an installed module, run the following command from the project root:

```bash
php artisan module:delete
```

## Select the Module

When you run the command, a list of all installed modules will be displayed.

Use the **↑** and **↓** arrow keys to select the module you want to delete and press **Enter** to continue.

## Delete Module Configuration

Next, the wizard will ask if you also want to delete the configuration stored by the module.

This configuration corresponds to the `settings.php` file, located in the `env` directory, where the module's persistent configuration values are stored.

Answer:

- **yes** to delete the module configuration.
- **no** to keep it.

Keeping the configuration can be useful if you plan to reinstall the module later and want to retain its previous configuration.

## Confirm Deletion

Before deleting the module, the wizard will request a first confirmation.

Type:

```text
yes
```

to continue with the process or:

```text
no
```

to cancel it.

## Configuration Deletion Confirmation

If in the previous step you chose to delete the configuration (`yes`), the wizard will request a second confirmation indicating that the configuration will be permanently deleted.

Confirm the operation by typing again:

```text
yes
```

or cancel it by typing:

```text
no
```

## Complete the Process

Once the operations are confirmed, Elymod will remove the module from the project along with all its files.

> **Important:** For security reasons, this command **does not delete the module's database**, its tables, or the information stored in them.

Database deletion must be done manually if you really want to delete the information.

This decision prevents accidental data loss and allows you to reinstall the module later while retaining existing information if you wish.

The command only deletes:

- The module directory.
- Its dependencies.
- Files generated during installation.
- Persistent configuration (only if you decided to delete it).

The information stored in the database will remain intact until manually deleted.
