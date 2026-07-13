# Groups

**Groups** represent the highest level of the authorization model. Their primary purpose is to **categorize and organize services** according to an organizational context or functional area.

A Group does **not** grant permissions by itself. Instead, it serves as a logical container for related services.

```text
Group
└── Service
```

---

## Why use Groups?

In large organizations, the same service may be used by different departments or teams, each requiring different permissions.

For example, a service named **VPN** may be used by both administrators and developers, but each group requires a different set of capabilities.

```text id="wm1a70"
Administrator
└── VPN

Developer
└── VPN
```

Although both services are named **VPN**, they belong to different Groups and represent completely different contexts.

This separation allows service names to be reused without conflicts.

For example, the following structure is perfectly valid:

```text id="p2xjlwm"
Administrator
└── VPN

Developer
└── VPN

Support
└── VPN
```

However, duplicate service names are **not allowed** within the same Group.

```text id="2hbq5s"
Administrator
├── VPN
└── VPN   ✗ Not allowed
```

This guarantees that every Service has a unique identifier within its Group.

---

# Services

A **Service** represents a feature, module, or functional area of the platform.

It defines **what part of the system** is being protected, but **not which actions** users may perform.

Examples of Services include:

* VPN
* Ecommerce
* CMS
* Billing
* Inventory
* Reports
* Users
* Dashboard

Every Service belongs to a single Group.

```text id="4s7lhi"
Administrator
├── VPN
├── Users
└── Dashboard

Developer
├── VPN
└── Reports

Customer
└── Ecommerce
```

This allows the same Service name to exist in different organizational contexts.

For example:

```text id="jlwmc9"
Administrator
└── VPN
    ├── Manage servers
    ├── Create users
    └── Delete configurations

Developer
└── VPN
    ├── View servers
    ├── Download configurations
    └── Review logs
```

Although both Services are named **VPN**, they represent different responsibilities.

---

# Roles

**Roles** define the actions that can be performed on a Service.

While a Service identifies **what** is being accessed, a Role specifies **which operation** is permitted.

Typical Roles include:

* Read
* Write
* Create
* Update
* Delete
* Execute
* Export
* Import

Roles are fully customizable.

Each Service can define only the operations it requires.

For example:

```text id="jlwmt6"
VPN
├── Read
├── Create
├── Update
└── Delete
```

Another Service might define a completely different set of Roles:

```text id="rm6nkm"
Reports
├── Read
├── Export
└── Share
```

---

# Scopes

A **Scope** is created by combining a **Group**, a **Service**, and a **Role**.

```text id="lr0go8"
Group + Service + Role = Scope
```

For example:

```text id="7j4by8"
Administrator + VPN + Read
        ↓
administrator_vpn_read
```

Another example:

```text id="wqf6hl"
Developer + VPN + Read
        ↓
developer_vpn_read
```

A Scope represents the smallest authorization unit within the platform.

Each Scope also has a unique internal identifier called **`gsr_id`**, which can be used to reference it safely without relying on its display name.

---

# Authorization Model

```text id="fjk3na"
                    Group
                      │
        ┌─────────────┴─────────────┐
        │                           │
 Administrator                Developer
        │                           │
        │                           │
       VPN                         VPN
        │                           │
  ┌─────┴─────┐              ┌──────┴──────┐
  │           │              │             │
 Read      Write          Read         Deploy
  │           │              │             │
  └─────┬─────┘              └──────┬──────┘
        │                           │
 administrator_vpn_read     developer_vpn_read
```

---

# Assigning Scopes

Scopes are not created manually.

Before a Scope can exist, the following elements must be defined:

1. A Group.
2. A Service.
3. One or more Roles for that Service.

Once Roles have been added to a Service, OAuth2 Passport Server automatically generates the corresponding Scopes.

These Scopes become available from the administration panel:

```text id="au4y1g"
Users
└── User Details
    └── Manage Scopes
```

Administrators can then assign only the permissions required by each user.

For example:

```text id="jlwm8k"
✓ administrator_vpn_read
✓ administrator_vpn_write
✗ administrator_vpn_delete
```

In this example, the user can view and modify VPN-related resources but cannot delete them.

---

# Benefits of this Authorization Model

Unlike traditional permission systems, OAuth2 Passport Server organizes authorization into three clearly defined layers:

* **Group** — Defines the organizational or functional context.
* **Service** — Represents a feature or module within the platform.
* **Role** — Specifies the operation that can be performed on that Service.

Combining these three components produces a unique Scope, enabling extremely granular access control across the entire platform.

This architecture offers several advantages:

* Reuse Service names across different organizational contexts.
* Eliminate naming conflicts.
* Simplify permission management.
* Build highly flexible authorization models.
* Scale naturally as new modules and services are added.
