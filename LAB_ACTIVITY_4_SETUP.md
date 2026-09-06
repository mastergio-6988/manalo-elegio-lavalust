# Laboratory Activity No. 4 Setup

This project is configured for the LavaLust Database and MVC laboratory activity.

## Database

1. Connect to MySQL with Navicat. For Aiven, use the Aiven host, assigned port, `avnadmin` username, password, and CA certificate from the Aiven service overview. Do not use `localhost` or port `3306` for Aiven unless Aiven specifically shows those values.
2. Run `database/lab4_users.sql` in Navicat. It creates `mydb`, creates the `users` table, inserts five sample records, and selects the rows for verification.
3. Confirm the `users` table has these columns: `id`, `firstname`, `lastname`, `email`, and `username`.

## LavaLust Environment

1. Copy `.env.example` to `.env` if `.env` does not already exist.
2. Set the database values in `.env` or in Render environment variables:

```env
DB_HOST=your-mysql-host
DB_PORT=your-mysql-port
DB_USERNAME=your-mysql-user
DB_PASSWORD=your-mysql-password
DB_DATABASE=mydb
```

Alternatively, set one MySQL URL value:

```env
DATABASE_URL=mysql://your-mysql-user:your-mysql-password@your-mysql-host:your-mysql-port/mydb
```

For older local copies, `DB_USER` and `DB_NAME` are also supported.

For Aiven TLS, set `DB_SSL_CA` to the absolute path of the downloaded CA certificate. Example:

```env
DB_SSL_CA=/home/mastergio/Downloads/ca.pem
DB_SSL_VERIFY=true
```

Do not commit `.env` and do not expose passwords in screenshots.

## MVC Files

The Lab 4 MVC pieces are already present:

- Model: `app/models/UsersModel.php` uses the `users` table.
- Controller: `app/controllers/UsersController.php` calls `$this->UsersModel->all()`.
- View: `app/views/users.php` loops through `$users` and displays an HTML table.
- Route: `app/config/routes.php` maps `/users` to `UsersController::index`.

## Test

Start the LavaLust development server:

```bash
php lava serve 3000
```

Open:

```text
http://127.0.0.1:3000/users
```

The page should display the five users from `mydb.users`.

## Submission Checklist

- Database screenshot showing `mydb`, the `users` structure, and inserted records.
- Database configuration screenshot from `app/config/database.php`, with no password shown.
- Model screenshot of `UsersModel`.
- Controller screenshot of `UsersController` showing `all()`.
- View screenshot showing the dynamic loop and table.
- Route screenshot showing `/users`.
- Browser output screenshot showing `/users` with the dynamic user table.
