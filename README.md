# StarCoffee - Online Coffee Ordering System

StarCoffee is a PHP and MySQL coffee ordering demo with a public storefront, customer accounts, a shopping cart, checkout, email notifications, and an admin panel.

## Requirements

- PHP 8.2+ with the `mysqli` and `mbstring` extensions
- MySQL 5.7+ or MariaDB 10.4+
- Apache, Nginx, or PHP's built-in development server

## Setup

1. Create a MySQL database named `starcoffee` and import [`starcoffee.sql`](./starcoffee.sql).
2. Set the environment variables listed in [`.env.example`](./.env.example) in the environment that runs PHP. PHP does not load `.env.example` automatically. `DB_USER` and `DB_PASSWORD` must be set; use a dedicated database user with a strong password.
3. Start a local development server from the project directory:

   ```sh
   php -S localhost:8000
   ```

4. Visit `http://localhost:8000`.

The SQL dump retains category and product catalog data but intentionally contains no admin account, customer accounts, contacts, orders, or reviews. Create the first admin account yourself:

1. Generate a password hash with PHP CLI (replace the example password with a unique password):

   ```sh
   php -r "echo password_hash('Replace-With-A-Unique-Password', PASSWORD_DEFAULT), PHP_EOL;"
   ```

2. Insert the admin email and generated hash into the database. Replace the placeholder with the hash output:

   ```sql
   INSERT INTO admin (a_email, a_pwd, a_status)
   VALUES ('admin@example.com', '<paste-generated-password-hash>', 1);
   ```

3. Sign in at `http://localhost:8000/admin/login.php`.

For an existing database, expand the admin password column before using hashed admin passwords:

```sql
ALTER TABLE admin MODIFY a_pwd VARCHAR(255) NOT NULL;
```

Existing plaintext admin passwords will no longer authenticate. Generate a password hash as above and replace the stored value for each admin account before deploying this version.

## Email

Order email notifications use PHPMailer and SMTP. Set `SMTP_HOST`, `SMTP_PORT`, `SMTP_SECURE`, `SMTP_USERNAME`, `SMTP_PASSWORD`, and optionally `SMTP_FROM_EMAIL` / `SMTP_FROM_NAME` in the PHP process environment. Do not put live credentials in source files or commit them. Without SMTP credentials, the application logs that email is not configured and does not send mail.

## Project layout

- `admin/` - Admin login, dashboard, and catalog/order management
- `assets/` - CSS, JavaScript, and site graphics
- `category-images/`, `product-images/` - Catalog images
- `inc/` - Shared storefront layout and mail helper
- `mail/` - SMTP configuration reader
- `cart.php`, `checkout.php` - Cart and checkout flow
- `starcoffee.sql` - Database schema and catalog seed data

## Notes

- Keep local secrets in environment variables; `.env` files are ignored by Git and are not read automatically by PHP.
- The SQL dump does not include personal records or a default admin login.
- GitHub Pages cannot execute PHP or provide MySQL. Deploy to a PHP-compatible host with a MySQL/MariaDB database.
