# OAuth2 Passport Server

### Modular Identity Provider and Access Management Platform

OAuth2 Passport Server is a modern Identity and Access Management (IAM) platform designed to centralize authentication, authorization, and permissions across applications, APIs, microservices, and enterprise systems.

Built on OAuth2, OpenID Connect (OIDC), and Elymod, it provides a centralized Identity Provider (IdP) for managing users, applications, permissions, subscriptions, and services from a single system.

Designed for SaaS platforms, startups, and enterprise architectures, it eliminates fragmented authentication systems while remaining fully compliant with industry standards.

---

## Problem Statement

As systems grow, identity becomes fragmented across:

- Web applications
- Mobile applications
- APIs
- Microservices
- Internal tools
- External integrations

This leads to:

- Duplicate user accounts
- Inconsistent permissions
- High maintenance cost
- Security risks
- Difficult integrations

---

## Solution

OAuth2 Passport Server centralizes identity while separating business logic through a modular architecture.

```text
Identity & Security → Core Platform
Business Features   → Modules
```

The core handles:

- Authentication
- Authorization
- OAuth2 / OpenID Connect
- Identity management
- Access control

Everything else is implemented as independent modules.

---

## Modular Architecture

### Plug-and-Play Modules

Modules can be installed, updated, enabled, or removed without modifying the core system.

Each module is fully isolated but integrated with the Identity Provider.

### Dependency Isolation

Each module can manage its own:

- Composer dependencies
- Node.js dependencies
- Build pipelines
- Internal architecture

This prevents dependency conflicts across the ecosystem.

### Fault Isolation

If a module fails:

```text
Module Failure → Module Disabled → Core Continues Running
```

---

## Key Features

### Identity & Access Management (IAM)

Centralized authentication and authorization across all systems.

### OAuth2 Authorization Server

Supports:

- Authorization Code Flow
- PKCE
- Refresh Tokens

### OpenID Connect Provider

Includes:

- Discovery endpoint
- JWKS endpoint
- ID tokens
- Standard OIDC flows

### Single Sign-On (SSO)

Authenticate once and access multiple applications.

---

## Permission System

Hierarchical scope-based model:

```text
group_service_action
```

Examples:

```text
billing_invoices_read
billing_payments_manage
crm_contacts_update
support_tickets_close
```

Benefits:

- Fine-grained control
- Consistent naming
- Easier auditing
- Cross-service compatibility

---

## Service Marketplace

Services can be published and accessed via subscriptions.

When a user subscribes, permissions (scopes) are assigned automatically.

---

## Microservice Ready

Designed for:

- Microservices
- SOA architectures
- API ecosystems

Tokens can be validated via OpenID Connect without direct database access.

---

## OpenID Connect Discovery

Supports automatic discovery via:

```
/.well-known/openid-configuration
```

Includes:

- Issuer
- Authorization endpoint
- Token endpoint
- JWKS endpoint
- Supported scopes
- Supported grants

---

## Elymod Ecosystem

OAuth2 Passport Server is built on **Elymod**, a modular framework for extending functionality without modifying the core system.

### Components

- Elymod → module lifecycle management
- Elymod App → standard module structure
- Elyscope → dependency isolation (Composer + PHP-Scoper bridge)

---

## Architecture

```
OAuth2 Passport Server
        ↓
Laravel Runtime
        ↓
Elymod
        ↓
Elymod App
        ↓
Elyscope
```

---

## Security

- OAuth2 compliant
- OpenID Connect compliant
- PKCE support
- JWT (RS256)
- JWKS support
- Centralized authorization
- Scope-based access control

---

## Enterprise Use Cases

- SaaS platforms
- Enterprises
- Government systems
- API platforms
- Multi-service ecosystems
- Customer portals

---

## Vision

A modular identity platform where authentication and authorization act as a stable foundation, while all business features evolve independently through isolated modules.

---

## Documentation

- [Development guide](docs/dev/developers_en.md)
- [Development guide in Spanish](docs/dev/developers_es.md)
- [Production deployment guide](docs/deploy/deploy_en.md)
- [Production deployment guide in Spanish](docs/deploy/deploy_es.md)
- [Changelog](CHANGELOG.md)
