# Architecture & Technical Decisions (SOLUTIONS)

This document outlines the rationale behind the technical choices, infrastructure architecture, and implementation details for the Application & Ticket Management System.

---

## 1. Infrastructure Architecture

### Debian Ecosystem over Alpine
To guarantee maximum runtime consistency, flawless package compilation, and predictable network behavior, both the PHP-FPM and Nginx layers are structurally built on top of stable Debian distributions (`php:8.4-fpm` and standard Nginx Debian-based images).
* **Why:** Alpine Linux uses `musl libc` instead of `glibc`, which frequently introduces edge-case bugs during the compilation of advanced binaries. This is specifically critical for advanced image optimization utilities (like `jpegoptim`, `optipng`, `pngquant`) required by `spatie/laravel-medialibrary`.

### Host-Container Permission Alignment
The application `Dockerfile` creates a non-root system user `www` explicitly mapped to `UID 1000` and `GID 1000`.
* **Why:** This directly mirrors the default user configuration of modern Linux development environments (specifically Arch Linux hosts). It prevents file permission fragmentation, allowing both the host developer and the internal container processes to read/write to the shared volumes (`app/`, `storage/`, `public/`) seamlessly without requiring `sudo` or triggering "Permission Denied" blocks.

### Encapsulated Environment Manifests
All orchestration configurations, virtual hosts, and lifecycle scripts are strictly encapsulated inside the `docker/` directory.
* **Why:** Keeps the project root clean, maintainable, and highly scannable, leaving only standard framework configurations visible.

### Host Port Conflict Resolution (IP Separation via `127.0.0.2`)
**Problem:** Local development environments frequently run native web servers (e.g., Nginx, Apache) or other Docker setups directly on the host machine, binding to `127.0.0.1:80` or `127.0.0.1:443`. Binding the project's ingress container directly to standard localhost leads to immediate port allocation conflicts (`address already in use`).

**Solution:** Isolate the entire project's network ingress by explicitly binding the `sh-web` container ports to an alternative loopback address (`127.0.0.2`):
```yaml
ports:
  - "127.0.0.2:80:80"
  - "127.0.0.2:443:443"
```
* **Why:** Linux natively treats the entire `127.0.0.0/8` block as routing to the local loopback interface. Utilizing `127.0.0.2` completely decouples this infrastructure from any services running on `127.0.0.1`, avoiding port collisions entirely without forcing the developer to change standard HTTP/HTTPS ports to obscure numbers (like `:8080` or `:8443`).

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

## 3. Configuration & Cryptography Isolation

### Single-Variable Domain Management (`APP_DOMAIN`)
A unified `APP_DOMAIN` environment variable acts as the single source of truth for host identification.
* **Why:** Traditional multi-container setups often split domain names into separate configuration lines for web servers (`NGINX_HOST`) and frameworks (`APP_URL`). This increases human error surface. By passing a bare string (e.g., `sh-crm.local`), Docker Compose dynamically constructs the correct format for both dependent environments (injecting protocols where necessary) without manual manipulation.

### Sealed Directory Volatility (SSL Certificates)
Local development requires SSL/TLS termination, yet cryptographic keys must remain transient.
* **Why:** Local SSL certificates contain absolute paths or unique host definitions that conflict across machines and expire naturally. By placing a target-scoped `.gitignore` directly within `docker/nginx/certs/`, we ensure directory structure permanence inside the repository while reliably filtering actual security artifacts out of Git history.