# Laboratory Exercise No. 6

## Product CRUD with React and LavaLust API

This repository contains a standalone React/Vite frontend in `frontend/`, a LavaLust JSON API, JWT login, and migrations for `products` and `refresh_tokens`. The React client follows the reference flow: login, product list, inline add/edit form, and delete confirmation. The legacy server-rendered product pages remain available separately.

### 1. Configure MySQL / Aiven

Copy `.env.example` to `.env` and set the connection values from Aiven: host, port, database name, username, password, and the CA certificate path. Keep `.env` private; it is ignored by Git. In Navicat, create a MySQL connection using the same host, port, username, and database. Enable SSL and select Aiven's CA certificate if required by your Aiven service. The app and Navicat are separate clients that connect to the same database.

For a local MySQL server, use its host and port (often `127.0.0.1:3306`) and adjust SSL settings to match that server.

### 2. Configure API login keys

Set a unique `PRODUCT_ADMIN_USERNAME` and strong `PRODUCT_ADMIN_PASSWORD` in `.env`. Generate independent JWT keys:

```sh
php lava jwt:generate
```

The command writes `JWT_SECRET` and `REFRESH_TOKEN_KEY` to `.env`. Never commit these values. For Render, add both keys and the admin credentials as environment variables in the API service settings. Set `API_ALLOWED_ORIGIN` to the exact frontend origin if the React page is hosted on a different domain; the same-origin `/lab6/` page works with its default setting.

### 3. Create the tables

Run the repository migrations:

```sh
php lava migration status
php lava migration run
```

This creates/records the `migrations`, `users`, `refresh_tokens`, and `products` tables. The API login uses the configured administrator credentials, while the token library stores refresh-token hashes in `refresh_tokens`. The lab's product table has `id`, `product_name`, `description`, `price`, `quantity`, and `created_at`.

Other migration actions are available:

```sh
php lava migration create-migration create_categories_table
php lava migration rollback
php lava migration rollback-all
php lava migration refresh
```

`rollback-all` and `refresh` remove application tables. Use them only against a development database.

### 4. Run locally

In one terminal, run the API:

```sh
php lava serve
```

In another terminal, run the Vite frontend:

```sh
cd frontend
npm install
npm run dev
```

Open `http://127.0.0.1:5173/login`. The Vite development proxy forwards `/api` requests to LavaLust on port 3000. Sign in using `PRODUCT_ADMIN_USERNAME` and `PRODUCT_ADMIN_PASSWORD`, then add, edit, and delete products. The browser never connects directly to MySQL.

### 5. API endpoints

| Method | Endpoint | Access |
| --- | --- | --- |
| `POST` | `/api/auth/login` | Public; returns access and refresh tokens |
| `POST` | `/api/auth/refresh` | Public; rotates a valid refresh token |
| `POST` | `/api/auth/logout` | Bearer token required |
| `GET` | `/api/products` | Bearer token required |
| `POST` | `/api/products` | Bearer token required |
| `PUT` / `PATCH` | `/api/products/{id}` | Bearer token required |
| `DELETE` | `/api/products/{id}` | Bearer token required |

The API returns JSON and validates product names, non-negative prices, and whole-number quantities. Each create, update, and delete request also requires the administrator to re-enter the configured username and password; the API checks these credentials before changing data. The API helper rejects missing or weak JWT keys at startup.

### 6. Render and Aiven deployment

Deploy the LavaLust API service to Render using the included Docker configuration. Add the Aiven CA certificate as a Render secret file at `/etc/secrets/aiven-ca.pem`, then set `APP_ENV`, the Aiven `DB_*` values, generated JWT keys, and administrator credentials in the service environment. Keep the database password and JWT keys in Render's environment settings, never in the repository. Run `php lava migration run` against the configured database as a one-off deployment command.

Deploy the React frontend as a Render Static Site from the separate frontend repository. Use build command `npm ci && npm run build`, publish directory `dist`, and build-time `VITE_API_URL` set to the LavaLust API origin. Set the API service's `API_ALLOWED_ORIGIN` to the exact frontend origin. Submit both repository URLs, the Render API URL, frontend URL, screenshots, and a working demonstration as required by the exercise.
