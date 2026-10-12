# PHP CRUD - Contact Book

A small project using PHP, SQLite, HTML, CSS and JavaScript.

## Run it
Requirements: PHP 7.4+ with the `pdo_sqlite` extension (enabled by default in most installs).

    cd php-crud
    php -S localhost:8000

Open http://localhost:8000

(With XAMPP: copy the folder into `htdocs` and open http://localhost/php-crud)

## Files
- index.php      - page with the form and the table
- api.php        - JSON API (GET, POST, PUT, DELETE)
- db.php         - SQLite connection, creates the table on first run
- assets/app.js  - fetch() calls and page updates
- assets/style.css - styling

## API
| Method | URL            | Action          |
|--------|----------------|-----------------|
| GET    | api.php        | list contacts   |
| GET    | api.php?id=1   | get one         |
| POST   | api.php        | create          |
| PUT    | api.php?id=1   | update          |
| DELETE | api.php?id=1   | delete          |

Body for POST/PUT: {"name":"Asha","email":"asha@example.com","phone":"12345"}
