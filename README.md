# Application & Ticket Management System (Mini-CRM)

A production-ready Mini-CRM system designed to collect, validate, and manage client tickets.

**Tech Stack:** PHP 8.4/8.5, Laravel 13, MariaDB 11.4, Nginx.
**Default Locale:** Russian (`ru`) (with `en`, `uk`, `kk` runtime engines active).

---

## 1. Quick Start Guide

Follow these steps to spin up and initialize the entire environment locally:

### Step 1: Clone the Repository
```bash
git clone <repository-url>
cd sh-crm
```

### Step 2: Initialize Environment Variables
Copy the upstream configuration template to create your local `.env` instance:
```bash
cp .env.example .env
```
*Note: Ensure `APP_LOCALE=ru` is set in your `.env` to load the localized interface packages for the admin panel and system alerts.*

### Step 3: Generate Local SSL Certificates
Run the following directive to provision a 10-year self-signed certificate matching your configured domain. If you altered `APP_DOMAIN` in your `.env`, replace `<env('APP_DOMAIN')>` below with your custom value:
```bash
openssl req -x509 -nodes -days 3650 -newkey rsa:2048 \
  -keyout docker/nginx/certs/auth.key \
  -out docker/nginx/certs/auth.crt \
  -subj "/C=US/ST=State/L=City/O=Dev/OU=Local/CN=<env('APP_DOMAIN')>"
```

### Step 4: Boot up the Containers
Build the localized image manifests and start the environment services in the background:
```bash
docker compose up -d --build
```
*Note: On initial boot, the application container automatically manages `composer install`, evaluates frontend assets (`npm install` & `npm run build`), and runs outstanding database migrations.*

### Step 5: Configure Local DNS Routing
To prevent port collisions with any local web servers running on your host machine, the Docker network binds exclusively to the alternative loopback IP `127.0.0.2`.

Map your virtual local domain to this specific IP. Append the following entry to your host system's hosts file (typically `/etc/hosts` on Linux/macOS):
```text
127.0.0.2 <env('APP_DOMAIN')>
```

Once all container indicators resolve to a `healthy` status via `docker compose ps`, access the interface securely at: **`https://<env('APP_DOMAIN')>`**

---

## 2. Test & Mock Data

*(To be completed: Admin credentials, test user accounts, pre-seeded sample tickets, and mock customer data descriptions).*

---

## 3. API Documentation & Integration Examples

*(To be completed: Detailed API endpoints specification, payload examples for ticket submission, headers, response schemas, and validation rule behavior documentation).*

---

## 4. Widget Integration (Iframe Setup)

*(To be completed: HTML copy-paste code snippets for cross-origin embedding, CORS policy parameters, and security headers configuration for integration on third-party sites).*