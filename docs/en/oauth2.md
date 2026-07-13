# OAuth2 and OpenID Connect Clients

OAuth2 Passport Server allows you to register clients compatible with **OAuth2** and **OpenID Connect (OIDC)** to secure applications, APIs, and external services.

The platform distinguishes between two types of clients based on their purpose and trust level.

---

# Client Types

There are two areas where OAuth clients can be registered.

## Developer

The **Developer** section is intended for developers who need to register their own applications.

Typical use cases include:

* Web applications.
* Mobile applications.
* APIs.
* Third-party integrations.
* Development tools.

Each developer can only view and manage the clients they own.

---

## Administrator

The **Administrator** section is reserved for registering official applications owned by the organization.

These are considered **trusted applications** and typically include:

* Administration panels.
* Employee portals.
* Intranet systems.
* Corporate applications.
* Internal business tools.

Clients created in this section can take advantage of features designed specifically for trusted internal applications.

---

# Supported Prompts

OAuth2 Passport Server supports the standard OpenID Connect prompts:

* `consent`
* `login`
* `none`

In addition, the platform introduces a custom prompt:

```text id="gg8fj4"
internal
```

This prompt is available **only** for clients registered through the **Administrator** section.

Its purpose is to identify trusted internal applications.

When a client uses the **`internal`** prompt, the authorization server skips the user consent screen because the application has already been designated as trusted.

This provides a smoother authentication experience for official organizational applications where requesting user consent repeatedly would be unnecessary.

---

# Downloading Client Credentials

After registering a new client, the platform allows you to download a credentials file containing all the information required to integrate the application with the authorization server.

This file typically includes:

* Client ID
* Client Secret
* Redirect URIs
* Authorization server endpoints
* Basic client configuration

The credentials file should be stored securely, as it contains sensitive information required for authentication.

---

# Personal Access Clients

**Personal Access Tokens** allow users to generate API tokens directly from their own accounts.

Before users can create Personal Access Tokens, a **Personal Access Client** must first be registered.

Only a system administrator can perform this operation.

---

## Creating a Personal Access Client

From the administration panel, navigate to:

```text id="5nqarf"
Administrator
└── OAuth Clients
    └── Create Personal Access Client
```

Provide a descriptive name for the client and complete the registration process.

Once created, authorized users will be able to generate their own Personal Access Tokens.

---

# Generating API Keys

Users who belong to the **Developer** group can generate API tokens from:

```text id="mwmhvl"
Developer
└── API Keys
```

These tokens can be used to access OAuth2-protected APIs without executing the full authorization flow for every request.

This feature is particularly useful for:

* Automation scripts.
* System integrations.
* CLI tools.
* Backend services.
* Scheduled tasks.

---

# Recommendations

To maintain a secure OAuth2 environment, consider the following best practices:

* Register official organizational applications only through the **Administrator** section.
* Use the `internal` prompt exclusively for fully trusted applications.
* Never expose or share a **Client Secret** publicly.
* Store downloaded client credentials in a secure location.
* Create at least one **Personal Access Client** before allowing developers to generate Personal Access Tokens.

Following these recommendations will help you securely manage both trusted internal applications and third-party integrations while maintaining a consistent and secure authorization infrastructure.
