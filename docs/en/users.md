# Users

The **Users** module allows administrators to manage registered accounts and control access by assigning **Scopes**.

All user management operations are protected by the authorization model based on **Groups**, **Services**, and **Roles**, providing fine-grained access control across the platform.

---

# Features

The Users module currently provides the following functionality:

* Create new users.
* Update user information.
* Manage assigned Scopes.
* Enable user accounts.
* Disable user accounts.

At this time, **permanent user deletion is not supported**.

This design helps preserve data integrity and prevents the accidental loss of information, since users may be referenced by audit logs, historical records, resources created by other modules, or other relational data throughout the system.

---

# Creating Users

When a new user is created from the administration panel, the system automatically generates a **secure random password**.

After the account has been created, the user receives an email containing their login credentials and instructions for accessing the platform.

For this reason, it is recommended to configure the email service before creating users.

```text
Settings
└── Email
```

If the email service has not been configured, the user account will still be created successfully, but no welcome email or initial password will be sent.

In this case, an administrator must provide the credentials manually or ask the user to reset their password.

---

# Accessing the Users Module

There are two ways to access the Users module.

## System Administrator

The primary system administrator has full access by default.

No additional Scopes need to be assigned.

---

## Through Scopes

User management can also be delegated to other members of the organization.

To grant full access, assign the **Full** Scope of the **Users** service within the **Administrator** group.

```text
administrator_users_full
```

This Scope grants complete access to every feature available in the Users module.

---

# Granular Access Control

The authorization system allows permissions to be assigned with a high level of granularity.

For example, the **Users** service may expose the following roles:

```text
Administrator
└── Users
    ├── Read
    ├── Create
    ├── Update
    ├── Manage Scope
    ├── Enable
    ├── Disable
    └── Full
```

This makes it possible to create users with only the permissions they actually require.

Example:

```text
✓ administrator_users_read
✓ administrator_users_create
✗ administrator_users_manage_scope
✗ administrator_users_disable
✗ administrator_users_full
```

In this example, the user can view existing users and create new ones but cannot manage permissions or enable and disable accounts.

---

# Full Scope

The following Scope:

```text
administrator_users_full
```

grants unrestricted access to the **Users** service.

It is intended for administrators or personnel responsible for user management and avoids the need to assign each individual Scope separately.

This permission should only be granted to users who require complete administrative access to the Users module.

---

# Managing Scopes

User permissions can be managed from:

```text
Users
└── User Details
    └── Manage Scopes
```

From this section, administrators can search for services and assign or revoke the corresponding Scopes.

Changes take effect immediately and determine which features and resources the user can access throughout the platform.

---

# Recommendations

Follow the **principle of least privilege** whenever possible by assigning only the Scopes required for each user's responsibilities.

Applying this principle improves system security, simplifies permission management, and reduces the risk of unauthorized access or accidental modifications.
