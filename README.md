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

### Database Ingestion & Seeding
To migrate the database schema, construct Spatie roles/permissions mapping, and seed administrative entities, execute:
```bash
docker compose exec sh-web php artisan migrate --seed
```

### Seeded Structural Entities
The default database state includes the following records:
* **Roles & Permissions:** Creates a `manager` role authorized to view administrative spaces.
* **Administrative Account:** Provisions a user inside Filament v5 accessible via:
    * **URL:** `https://<env('APP_DOMAIN')>/admin`
    * **Username:** `admin@<env('APP_DOMAIN')>`
    * **Password:** `secret123`

### Generating Bulk Mock Records via Factories
To quickly seed the system with realistic customer metrics and ticket volume for performance evaluation, run the factory sequences via Artisan Tinker:
```bash
docker compose exec sh-web php artisan tinker
```
Inside the interactive shell, execute the model factory states:
```php
// Generates 10 customers, each containing 2-3 contextual support tickets
\App\Models\Customer::factory()->count(10)->create()->each(function ($customer) {
    \App\Models\Ticket::factory()->count(rand(2, 3))->create([
        'customer_id' => $customer->id
    ]);
});
```

---

## 3. API Documentation & Integration Examples

The architecture provides an interactive Swagger UI interface tracking the OpenAPI specifications for downstream clients.
* **Interactive Dashboard:** `https://<env('APP_DOMAIN')>/api/documentation/v1`
* **Specification Compilation:** If schemas or controllers are modified, re-generate files via:
  ```bash
  docker compose exec sh-web php artisan l5-swagger:generate
  ```

### Direct Integration Snippets (The 2 Core Endpoints)

#### 1. Submit a Support Ticket (`POST /api/v1/tickets`)
Processes public payload data, initializes unique customer mapping on identity match, stores attachments, and applies strict 24-hour frequency rate limiting per user identity.

* **Example Request (cURL Multipart Form Data):**
```bash
curl -X POST https://<env('APP_DOMAIN')>/api/v1/tickets \
  -H "Accept: application/json" \
  -H "Accept-Language: ru" \
  -F "name=Alex Oskiller" \
  -F "email=oskiller@example.com" \
  -F "phone=+77012345678" \
  -F "subject=Integration Error" \
  -F "message=The reactive iframe layout collapses on low-res frames." \
  -F "attachments[]=@/path/to/screenshot.png"
```

* **Successful Response (201 Created):**
```json
{
  "success": true,
  "message": "Заявка успешно зарегистрирована.",
  "data": {
    "ticket_id": 999
  }
}
```

* **Rate Limited Error Response (422 Unprocessable Entity):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "ticket": [
      "Заявку можно отправить лишь раз в 24 часа."
    ]
  }
}
```

#### 2. Fetch Anonymous Telemetry & Statistics (`GET /api/v1/tickets/statistics`)
A public endpoint used by embeddable frames to display layout widgets showing processing efficiency across time slices.

* **Example Request (cURL):**
```bash
curl -X GET https://<env('APP_DOMAIN')>/api/v1/tickets/statistics \
  -H "Accept: application/json"
```

* **Successful Response (200 OK):**
```json
{
  "success": true,
  "metrics": {
    "day": {
      "total_tickets": 12,
      "new": 4,
      "in_progress": 5,
      "processed": 3
    },
    "week": {
      "total_tickets": 84,
      "new": 10,
      "in_progress": 24,
      "processed": 50
    },
    "month": {
      "total_tickets": 320,
      "new": 15,
      "in_progress": 45,
      "processed": 260
    }
  }
}
```

---

## 4. Widget Integration (Iframe Setup)

To embed the reactive, cross-origin feedback widget workspace into any external third-party ecosystem, inject the following HTML placeholder layout into the target view layer:

```html
<iframe 
    src="https://<env('APP_DOMAIN')>/widget?lang=ru" 
    style="width: 100%; border: none; min-height: 550px; overflow: hidden; background: transparent;" 
    scrolling="no" 
    loading="lazy"
    allow="clipboard-write">
</iframe>
```

### Localization Dynamic Switching
The workspace reads the initial language configuration directly from the target frame query payload:
* `lang=ru` — Russian core template matching localization standard maps.
* `lang=en` — English language system variables.
* `lang=uk` — Ukrainian translation matrices.
* `lang=kk` — Kazakh localized layouts.

### Cross-Origin Security & Headers Parameters
To ensure proper operation across different origins inside the iframe ecosystem, the server configuration uses specific HTTP security headers:

1. **X-Frame-Options Bypassing:** For the dynamic route `/widget` (and `/feedback-widget`), the traditional `X-Frame-Options: SAMEORIGIN` safety directive is explicitly removed by the application middleware layer to permit standard cross-origin rendering.
2. **Content Security Policy (CSP):** The application relies on modern `frame-ancestors` controls. To restrict embed interactions exclusively to authorized partner nodes, adjust the environment headers to match targeted domain scopes:
   ```text
   Content-Security-Policy: frame-ancestors 'self' https://*.trusted-partner.com;
   ```
   *Note: For completely anonymous universal integrations, the directive can be configured with a generic wildcard scope (`frame-ancestors 'self' *;`).*
3. **Cross-Origin Resource Sharing (CORS):** The asynchronous API endpoint layer (`POST /api/v1/tickets`) strictly references the configured application domain mappings inside `config/cors.php`, handling cross-site AJAX requests safely with proper preflight configurations.