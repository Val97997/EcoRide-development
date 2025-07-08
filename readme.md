## Running the Project with Docker

This project provides a complete Docker-based setup for local development and testing. The stack includes PHP 8.3 (FPM, Alpine), MySQL, Redis, and MongoDB, as required by the application and its dependencies.

### Project-Specific Requirements
- **PHP Version:** 8.3 (FPM, Alpine)
- **Composer:** Installed in the build process
- **PHP Extensions:** `opcache`, `iconv`, `soap`, `zip`, `intl`, `fileinfo`, `pdo`, `redis`, `mysqli`, `pdo_mysql`, `gd`, `mongodb`
- **Services:**
  - PHP application (FPM)
  - MySQL
  - Redis
  - MongoDB

### Environment Variables
- The application supports environment configuration via `.env` files (`.env`, `.env.dev`, `.env.local`, `.env.test`).
- MySQL service uses the following variables (set in `compose.yaml`):
  - `MYSQL_ROOT_PASSWORD` (default: `rootpassword` — change for production)
  - `MYSQL_DATABASE` (default: `app`)
  - `MYSQL_USER` (default: `appuser`)
  - `MYSQL_PASSWORD` (default: `apppassword`)

> **Note:** Uncomment the `env_file: ./.env` line in the `php-app` service in `compose.yaml` if you want Docker Compose to load environment variables from your `.env` file.

### Build and Run Instructions
1. **Build and start all services:**
   ```sh
   docker compose up --build
   ```
   This will build the PHP application image and start all required services (php-app, mysql, redis, mongodb).

2. **Accessing the Application:**
   - The PHP-FPM service exposes port **9000** internally. You will need a web server (e.g., Nginx or Caddy) to proxy HTTP requests to PHP-FPM if you want to access the app via browser. (See `docker/nginx/` for an example Nginx config.)
   - MySQL: `localhost:3306`
   - Redis: `localhost:6379`
   - MongoDB: `localhost:27017`

### Special Configuration
- **Volumes:** Data for MySQL, Redis, and MongoDB is persisted in Docker volumes (`mysql-data`, `redis-data`, `mongo-data`).
- **User/Permissions:** The PHP container runs as a non-root user (`appuser`) for security. Cache and log directories are pre-created and owned by this user.
- **Healthchecks:** All database services include healthchecks for reliable startup.
- **Composer:** Dependencies are installed during the build process; no need to run `composer install` manually.

### Ports Exposed
- **php-app:** 9000 (internal, for FPM)
- **mysql:** 3306
- **redis:** 6379
- **mongodb:** 27017

---

For further customization (e.g., adding a web server), refer to the `docker/nginx/` directory and adjust your `compose.yaml` as needed.
