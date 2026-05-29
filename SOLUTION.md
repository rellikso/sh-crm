# Architecture & Technical Decisions (SOLUTIONS)

This document outlines the rationale behind the technical choices, infrastructure architecture, and implementation details for the Application & Ticket Management System.

---

## 1. Infrastructure & Network Architecture

### Host Port Conflict Resolution (IP Separation via `127.0.0.2`)
**Problem:** Local development environments frequently run native web servers (e.g., Nginx, Apache) or other Docker setups directly on the host machine, binding to `127.0.0.1:80` or `127.0.0.1:443`. Binding the project's ingress container directly to standard localhost leads to immediate port allocation conflicts (`address already in use`).

**Solution:** Isolate the entire project's network ingress by explicitly binding the `sh-web` container ports to an alternative loopback address (`127.0.0.2`):
```yaml
ports:
  - "127.0.0.2:80:80"
  - "127.0.0.2:443:443"
```
* **Why:** Linux natively treats the entire `127.0.0.0/8` block as routing to the local loopback interface. Utilizing `127.0.0.2` completely decouples this infrastructure from any services running on `127.0.0.1`, avoiding port collisions entirely without forcing the developer to change standard HTTP/HTTPS ports to obscure numbers (like `:8080` or `:8443`).

### Debian Ecosystem over Alpine
To guarantee maximum runtime consistency, flawless package compilation, and predictable network behavior, both the PHP-FPM and Nginx layers are structurally built on top of stable Debian distributions (`php:8.4-fpm` and standard Nginx Debian-based images).
* **Why:** Alpine Linux uses `musl libc` instead of `glibc`, which frequently introduces edge-case bugs during the compilation of advanced binaries. This is specifically critical for advanced image optimization utilities (like `jpegoptim`, `optipng`, `pngquant`) required by `spatie/laravel-medialibrary`.

### Host-Container Permission Alignment
The application `Dockerfile` creates a non-root system user `www` explicitly mapped to `UID 1000` and `GID 1000`.
* **Why:** This directly mirrors the default user configuration of modern Linux development environments (specifically Arch Linux hosts). It prevents file permission fragmentation, allowing both the host developer and the internal container processes to read/write to the shared volumes (`app/`, `storage/`, `public/`) seamlessly without requiring `sudo` or triggering "Permission Denied" blocks.

### Encapsulated Environment Manifests
All orchestration configurations, virtual hosts, and lifecycle scripts are strictly encapsulated inside the `docker/` directory.
* **Why:** Keeps the project root clean, maintainable, and highly scannable, leaving only standard framework configurations visible.

---

## 2. Container Health & Lifecycle Management

### Native MariaDB Healthchecks
The `sh-db` container leverages the native `healthcheck.sh` binary shipped with official MariaDB images rather than traditional `mysqladmin ping` utilities.
* **Why:** MariaDB applies a strict `unix_socket` authentication policy for the `root` user within container networks by default. Using standard CLI password flags over TCP connections inside a healthcheck directive causes predictable authentication failures (`Access Denied`), resulting in a perpetual `unhealthy` container state even when the daemon is fully responsive. `healthcheck.sh` securely bypasses this via internal socket checks.

### Smart Entrypoint Orchestration (`entrypoint.sh`)
The container boot process delegates asset preparation and schema safety checks directly to a dynamic shell runtime.
* **Conditional Asset Compilation:** The script evaluates the existence of `node_modules/`. If absent (e.g., fresh clone or volume purge), it triggers automated dependency retrieval and frontend building. If present, it skips compilation entirely, minimizing local stack startup times down to 3-5 seconds.
* **Database Readiness Guard:** Database migrations (`php artisan migrate`) are deferred until the orchestration layer confirms that the relational store has transitioned to a fully ready and accepting state.

---

## 3. Back-Office & Ecosystem Integration

### Core Back-Office Stack (Filament, Permissions, MediaLibrary)
To establish a robust administrative pipeline with zero boilerplate waste, the foundational stack includes Spatie's Permission and MediaLibrary alongside the Filament Panel Builder.
* **Why:** Media tracking and polymorphic access controls (ACL) are bound natively into the database layer via vendor-published migrations, securing media attachments and administrative routing natively through Eloquent.

### Choosing Filament for Admin Operations
**Problem:** Building a secure, interactive, and highly filterable back-office interface from scratch requires massive boilerplate overhead (routing, tables, state management, form validation, and ACL layouts).

**Solution:** The administration interface is built strictly on top of **Filament** (TALL Stack).
* **Why:** Filament operates natively within the Laravel lifecycle, eliminating the need to build and maintain a separate decoupled SPA (like Vue/React). It provides enterprise-ready CRUD operations, advanced data filtering, and relationship management out of the box. Additionally, it features excellent built-in localization blocks, enabling zero-overhead multi-language support.

---

## 4. Configuration, Cryptography & Localization

### Single-Variable Domain Management (`APP_DOMAIN`)
A unified `APP_DOMAIN` environment variable acts as the single source of truth for host identification.
* **Why:** Traditional multi-container setups often split domain names into separate configuration lines for web servers (`NGINX_HOST`) and frameworks (`APP_URL`). This increases human error surface. By passing a bare string (e.g., `sh-crm.local`), Docker Compose dynamically constructs the correct format for both dependent environments (injecting protocols where necessary) without manual manipulation.

### Sealed Directory Volatility (SSL Certificates)
Local development requires SSL/TLS termination, yet cryptographic keys must remain transient.
* **Why:** Local SSL certificates contain absolute paths or unique host definitions that conflict across machines and expire naturally. By placing a target-scoped `.gitignore` directly within `docker/nginx/certs/`, we ensure directory structure permanence inside the repository while reliably filtering actual security artifacts out of Git history.

### Vendor-Based Localization Architecture (`laravel-lang/common`)
To keep the application repository maintainable and free of redundant localized file scaffolding, localization is achieved strictly via `laravel-lang/common`.
* **Why:** Instead of committing thousands of lines of copied system language keys to the repository, translation assets reside securely inside the `vendor/` directory. The engine seamlessly serves standard framework alerts and validation strings for chosen environments at runtime without manual file updates.

### Interface Localization Strategy (`ru` / `en`)
The system's core administrative backend is hardcoded to use Russian (`ru`) as the primary locale, with an English (`en`) fallback.
* **Why:** Due to the distributed footprint of the organization (engineering and operational management spread across Ukraine and Kazakhstan), the choice serves strictly as a pragmatic, cross-border technical *lingua franca*. This ensures unified communication between regional administrators, prevents data schema interpretation conflicts, and aligns the CRM terminology across distinct legislative environments without splitting the codebase.

### Smart Punctuation Injector (Kazakh Locale Extension)
The `config/localization.php` manifest implements typographical formatting mappings (`smart_punctuation`) mapping `Locale::Kazakh` explicitly alongside default East European arrays.
* **Why:** Ensures that dynamic validation reports, layout arrays, and administrative interfaces render culturally accurate quotation marks («...») natively rather than generic double quotes (`"..."`), guaranteeing polished frontend output for local end-users.

---

## 5. API Documentation, Schema Contract & Versioning

### Automated OpenAPI Spec Generation via L5-Swagger
**Problem:** Frontend integration layers (specifically external iframe widgets) require strict, immutable API request/response contracts. Manual tracking of API documentation via external tools (like Postman or custom wikis) inevitably drifts from the actual source code over time, causing production runtime failures.

**Solution:** The system integrates **`darkaonline/l5-swagger`** to enforce **Documentation-as-Code** combined with an isolated multi-documentation architecture.
* **Why:** By using native **PHP 8.4+ Attributes** directly on Controllers, Request DTOs, and API Resources, the code becomes the single source of truth. The specification is auto-generated via standard CLI directives, completely eliminating documentation drift.
* **Security & Environments:** In local and staging environments, the interactive Swagger UI dashboard is fully accessible for debugging. For production environments, the engine serves raw JSON payloads to authorized consumers while restricting public access to the UI layout via middleware guards.

### Isolated Multi-Documentation Architecture (v1 / v2 Coexistence)
To maintain strict backward compatibility while evolving the system, the project implements decoupled documentation environments using explicit configurations. This prevents configuration bleeding and overlapping routing issues across sequential API versions.

#### 1. Configuration Matrix (`config/l5-swagger.php`)
The `documentations` block is split into independent scopes, assigning dedicated scanner roots and UI routes for each respective API generation:

```php
return [
    'default' => 'v1',
    'documentations' => [
        'v1' => [
            'api' => [
                'title' => 'Tech SMS API - Version 1.0',
            ],
            'routes' => [
                'api' => 'api/documentation/v1',
                'docs' => 'docs/v1',
                'oauth2-callback' => 'api/oauth2-callback/v1',
            ],
            'paths' => [
                'use_absolute_path' => env('L5_SWAGGER_USE_ABSOLUTE_PATH', true),
                'docs_json' => 'api-docs-v1.json',
                'docs_yaml' => 'api-docs-v1.yaml',
                'annotations' => [
                    base_path('app/Http/Controllers/Api/V1'),
                ],
            ],
        ],

        'v2' => [
            'api' => [
                'title' => 'Tech SMS API - Version 2.0 (NextGen)',
            ],
            'routes' => [
                'api' => 'api/documentation/v2',
                'docs' => 'docs/v2',
                'oauth2-callback' => 'api/oauth2-callback/v2',
            ],
            'paths' => [
                'use_absolute_path' => env('L5_SWAGGER_USE_ABSOLUTE_PATH', true),
                'docs_json' => 'api-docs-v2.json',
                'docs_yaml' => 'api-docs-v2.yaml',
                'annotations' => [
                    base_path('app/Http/Controllers/Api/V2'),
                ],
            ],
        ],
    ],
];
```

#### 2. Directory & Namespace Alignment
Controllers are strictly separated into versioned namespaces to align with the scanner configurations:

```text
app/Http/Controllers/Api/
├── V1/
│   ├── TicketController.php       -> #[OA\Get(path: "/tickets")] (Prefix: /api/v1 via Server block)
│   └── BaseV1OpenApi.php          -> Holds #[OA\Server(url: "https://.../api/v1")]
└── V2/
    ├── TicketController.php       -> #[OA\Get(path: "/tickets")] (Prefix: /api/v2 via Server block)
    └── BaseV2OpenApi.php          -> Holds #[OA\Server(url: "https://.../api/v2")]
```

* **Relative Paths:** Inside version-specific controllers, the `path` attribute stays relative (`path: "/tickets"`). The base route prefix context (`/api/v1` or `/api/v2`) is injected cleanly from the corresponding `BaseOpenApi` server configuration file to avoid double-prefixing URLs within Swagger UI.
* **Operation ID Uniqueness:** Every `operationId` attribute must be suffixed with its version tag (e.g., `getTicketStatisticsV1` vs. `getTicketStatisticsV2`) to guarantee collision-free JSON schema compilation.

#### 3. Specification Compilation
To re-compile OpenAPI specifications after code modifications, target either specific definitions or execute a global run:
```bash
# Generate all defined documentation blocks simultaneously
php artisan l5-swagger:generate --all

# Generate a specific isolated target version
php artisan l5-swagger:generate v1
php artisan l5-swagger:generate v2
```

---

## 6. Database Normalization & Customer Identity Guarantee

### Preventative Deduplication via Composite Keys
**Problem:** Inbound submissions from external iframe widgets often introduce data redundancy. Blindly creating a new customer record for every submitted ticket pollutes the database, breaks analytical integrity, and makes historic ticket mapping impossible.

**Solution:** The system enforces a strict composite database constraint.
* **Database Level:** The `customers` table uses a unique composite index: `$table->unique(['email', 'phone']);`. This acts as a hard database guard against race conditions and duplicates.
* **Application Level:** Instead of standard insert directives, the ingestion layer utilizes Eloquent's `updateOrCreate()`. This strategy dynamically self-heals customer metadata (e.g., updating their name if it changed) while linking the incoming ticket to a single, unified `customer_id`.

---

## 7. Role-Based Access Control & Panel Security (Filament v5)

### Decoupled Authentication Guarding
**Problem:** The core application must support multiple internal tiers (Admins who manage users/settings, and Managers who process client tickets) while explicitly preventing unauthorized authenticated application users from gaining entry to the backend system.

**Solution:** Leveraged **Spatie Laravel-Permission** combined with the native **Filament v5 Panel Access Contract**.
* **Role Ingestion:** Initial system states are strictly governed by a `RolesAndPermissionsSeeder`, establishing deterministic `admin` and `manager` nodes during deployment.
* **Panel Protection:** The `User` model implements Filament's `FilamentUser` interface. The `canAccessPanel(Panel $panel)` hook explicitly validates the user's Spatie roles before allowing entry into the Livewire v4 reactive administration interface, bypassing resource exposure risks.

---

## 8. Widget Architecture & Real-Time Data Synchronization

### Decoupled Asset Management (Vite & Tailwind v4)
**Problem:** Storing custom styles and raw JavaScript directly inside the external iframe template violates the separation of concerns, lacks minification, and bypasses cache-busting optimization.
**Solution:** The widget completely repurposes the default application entry points (`resources/css/app.css` and `resources/js/app.js`). Styling is driven entirely by **Tailwind v4**, utilizing explicit scanning directives (`@source '../views/widget/**/*.blade.php';`) to compile only the utility classes used by the iframe layout. These assets are compiled via Vite, ensuring the external widget remains ultra-lightweight and completely isolated from the heavy Filament administration bundle. The dynamic `API_BASE` path is cleanly passed to the compiled JavaScript execution context via an HTML `<meta name="api-base-url">` tag.

### Frontend Synchronization Strategy
**Problem:** When an external user submits a new ticket via the iframe widget, the displayed statistics ("New Tickets Last 24h") must reflect the change. Relying on optimistic UI updates (e.g., hardcoding `+1` via JavaScript) leads to data desynchronization if the widget has been open for an extended period, completely ignoring other tickets created system-wide during that timeframe.

**Evaluated Options:**
1. **WebSockets / Server-Sent Events (SSE):** Provides true real-time syncing. *Rejected:* Extreme overkill for a lightweight iframe. Adds massive infrastructure cost and persistent connection overhead for thousands of potential partner embeddings.
2. **Short Polling:** Pinging the API every 30 seconds to fetch fresh stats. *Rejected:* Causes unnecessary backend load and database strain from idle pages.
3. **Optimistic UI (+1 increment):** Blindly incrementing numbers on the client side upon successful form submission. *Rejected:* Creates false data states, hiding concurrent system activity from the user.

**Chosen Solution: Re-fetch on Action (Event-Driven Polling)**
The system implements a compromise architecture. The JavaScript client fetches statistics automatically *only* upon the initial DOM load, and specifically triggers a forced background re-fetch (`GET /api/v1/tickets/statistics`) immediately following a successful `201 Created` form submission.
* **Why:** This ensures absolute data consistency right when the user's attention is focused on the interface, without wasting server resources on WebSockets or idle polling.
* 