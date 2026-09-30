# Lumière API Database Setup

## Software

- Laravel API
- MySQL 8
- Laragon
- dbForge Studio for MySQL

## Database

Database name:

`lumiere_db`

## Laravel `.env` Configuration

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lumiere_db
DB_USERNAME=lumiere_api
DB_PASSWORD=YOUR_DATABASE_PASSWORD
```

> Replace `YOUR_DATABASE_PASSWORD` with the actual local MySQL password when running the project. Do not publish a real database password in a public repository.

## Dedicated Database User

Laravel connects using:

`lumiere_api@127.0.0.1`

The user is granted privileges only for the Lumière project database.

Example MySQL setup:

```sql
CREATE DATABASE IF NOT EXISTS lumiere_db;

CREATE USER IF NOT EXISTS
'lumiere_api'@'127.0.0.1'
IDENTIFIED BY 'YOUR_DATABASE_PASSWORD';

GRANT ALL PRIVILEGES
ON lumiere_db.*
TO 'lumiere_api'@'127.0.0.1';

FLUSH PRIVILEGES;
```

## Project Setup

1. Start Laragon.
2. Make sure MySQL is running.
3. Open the Laravel project:

```powershell
cd C:\laragon\www\lumiere_api
```

4. Configure `.env`.
5. Clear cached configuration:

```powershell
php artisan optimize:clear
```

6. Run migrations:

```powershell
php artisan migrate
```

7. Start Laravel:

```powershell
php artisan serve
```

API server:

`http://127.0.0.1:8000`

API base URL:

`http://127.0.0.1:8000/api`

## Verify API Routes

```powershell
php artisan route:list --path=api
```

The current project exposes 13 API routes.
