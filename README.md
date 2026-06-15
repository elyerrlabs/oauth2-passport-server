# OAuth2 Passport Server

A centralized Identity and Access Management (IAM) platform built on OAuth2 and OpenID Connect for Laravel applications.

OAuth2 Passport Server enables organizations to centralize authentication, authorization, and user permissions across multiple applications, services, APIs, and microservices through industry-standard protocols.

Instead of managing users, permissions, and authentication separately in every application, organizations can use a single authorization server to provide secure access management across their entire ecosystem.

---

## Overview

OAuth2 Passport Server acts as the central authority responsible for:

- User authentication
- Authorization management
- Access token issuance
- Identity federation
- Scope management
- Service subscriptions
- Single Sign-On (SSO) capabilities
- OpenID Connect integration

Applications and microservices delegate authentication and authorization to the authorization server, reducing complexity and improving security across the organization.

---

## OpenID Connect Support

The server implements OpenID Connect discovery endpoints, allowing applications and services to automatically discover configuration information.

Discovery endpoint:

```text
/.well-known/openid-configuration
```

Example:

```json
{
    "issuer": "https://auth.example.com",
    "authorization_endpoint": "/oauth/authorize",
    "token_endpoint": "/api/oauth/token",
    "jwks_uri": "/oauth/jwks",
    "end_session_endpoint": "/auth/logout"
}
```

This allows any OpenID Connect compatible application to integrate with the authorization server using standard protocols.

---

## Supported OAuth2 Flows

Currently supported:

### Authorization Code Flow

The recommended OAuth2 flow for server-side web applications.

Features:

- Secure authorization code exchange
- Refresh token support
- Consent management
- Session-based authentication

---

### Authorization Code Flow with PKCE

Designed for:

- Single Page Applications (SPA)
- Mobile applications
- Public clients

Features:

- Proof Key for Code Exchange (PKCE)
- Protection against authorization code interception attacks
- No client secret required

---

## Authentication Prompts

The platform currently supports:

### Internal Prompt

Custom authentication experience designed for trusted applications belonging to the organization.

Characteristics:

- Managed by administrators
- Available only for trusted first-party applications
- Organization-controlled user experience
- Enhanced security policies

This prompt type is intended for internal business applications and enterprise environments.

---

## Identity and Permission System

OAuth2 Passport Server introduces an advanced authorization model based on Scopes.

Unlike traditional role-based systems, scopes are generated using:

```text
service_group_role
```

Example:

```text
billing_admin_create
billing_admin_update
support_agent_read
sales_manager_reports
```

Each scope becomes a unique permission identifier that can be assigned independently.

This provides significantly more granular authorization compared to traditional role-based systems.

---

## Service Marketplace

The platform allows organizations to publish services that can be subscribed to by users.

Each service can expose one or multiple scopes.

Example:

```text
CRM Service
 ├── crm.read
 ├── crm.create
 ├── crm.update

Billing Service
 ├── billing.read
 ├── billing.manage
```

When users subscribe to a service:

- Scopes are assigned automatically
- Access becomes immediately available
- Applications receive updated authorization information through access tokens

This creates a flexible permission distribution model across the entire ecosystem.

---

## Microservice Integration

OAuth2 Passport Server is designed to be the central identity provider for distributed architectures.

Supported scenarios:

- Microservices
- Internal APIs
- SaaS applications
- Multi-tenant platforms
- Enterprise systems

Services can validate tokens using:

- JWKS endpoint
- OpenID Connect discovery
- Standard JWT verification

No direct database access is required between services.

---

## Job Processing

The platform includes integrated queue management capabilities.

Features:

- Horizon-based monitoring
- Background job processing
- Queue metrics and monitoring
- CSP-compatible dashboard modifications

The included Horizon integration has been adapted to work correctly in environments that enforce strict Content Security Policies (CSP).

---

## Security Features

- OAuth2 compliant
- OpenID Connect compliant
- RS256 token signing
- Refresh tokens
- PKCE support
- JWKS endpoint
- Token revocation
- Centralized authentication
- Scope-based authorization
- Content Security Policy support

---

## Architecture

```text
                +----------------------+
                | OAuth2 Passport      |
                | Authorization Server |
                +----------+-----------+
                           |
          +----------------+----------------+
          |                |                |
          v                v                v

      Web App         Mobile App      Microservice
          |                |                |
          +----------------+----------------+
                           |
                     OpenID Connect
                           |
                         OAuth2
```

---

## Vision

OAuth2 Passport Server aims to provide a complete identity platform for organizations that need centralized authentication, authorization, service subscriptions, and secure communication between applications and microservices while remaining fully compatible with open standards such as OAuth2 and OpenID Connect.

---

## Modular Architecture

OAuth2 Passport Server is built on top of **Elymod**, a modular application framework designed to extend platform capabilities without modifying the core application.

This architecture allows organizations, developers, and vendors to build and distribute independent modules while maintaining a clean upgrade path for the authorization server.

### Core Principles

- Zero core modifications
- Independent module lifecycle
- Isolated dependencies
- Vendor-friendly architecture
- Upgrade-safe extensions
- Feature-based development

Applications can evolve by installing or removing modules without affecting the integrity of the core authorization platform.

---

## Elymod Framework

Elymod provides a complete modular system where each module behaves as a self-contained application.

A module can contain:

- Controllers
- Models
- Routes
- Views
- Assets
- Database migrations
- Service providers
- Console commands
- Queue jobs
- API endpoints
- OAuth integrations

This allows teams to develop new features independently while keeping the main platform stable.

---

## Dependency Isolation

One of the main challenges of modular systems is dependency conflicts.

Elymod solves this by allowing modules to manage their own dependencies independently.

### PHP Dependencies

Modules can include their own Composer dependencies without impacting the main application or other modules.

### Node.js Dependencies

Modules can also maintain their own Node.js ecosystem, including:

- package.json
- build tools
- frontend frameworks
- asset pipelines

This prevents dependency collisions and allows different modules to evolve at their own pace.

---

## Independent Licensing

Each module can define its own licensing model.

Examples:

- Open Source modules
- Commercial modules
- Subscription-based modules
- Enterprise modules
- Internal organization modules

This enables the creation of a complete ecosystem around the platform.

```text
OAuth2 Passport Server
│
├── CRM Module (Commercial)
├── Billing Module (Commercial)
├── Analytics Module (Enterprise)
├── Helpdesk Module (Open Source)
└── Internal HR Module (Private)
```

Organizations can deploy only the modules they require while maintaining centralized authentication and authorization.

---

## Ecosystem Ready

Because the authorization server and Elymod share the same foundation, modules can integrate directly with:

- OAuth2
- OpenID Connect
- Scopes
- Service subscriptions
- User permissions
- Jobs and queues
- API gateways
- Internal services

This enables the creation of complete enterprise ecosystems powered by a single identity platform.

---

## Why Modular?

Traditional authorization servers focus only on authentication.

OAuth2 Passport Server extends this concept by providing a foundation for building entire business platforms around a centralized identity system.

Authentication becomes the starting point, while modules provide the business functionality.

The result is a platform capable of growing from a simple authorization server into a complete enterprise ecosystem without requiring core modifications.
