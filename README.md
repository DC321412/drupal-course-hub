# Course Hub

A course management site built with Drupal 11. It lists courses with filters, sorting and pagination, and includes a custom module and a custom theme.

## Features

- **Course** content type with title, description and image
- **Course Category** taxonomy for grouping courses
- **/courses** listing page (Views) with filters, sorting and pagination
- **course_hub** custom module: a page, a form with validation, a service and a custom block
- **course_theme** custom responsive theme (based on Stable 9) with Twig overrides and CSS/JS libraries
- User roles and permissions (the `access course hub` permission controls access to the module's pages)
- Site configuration stored in `config/sync`

## Requirements

- PHP 8.3 or newer
- Composer
- Drush (installed with the project through Composer)
- SQLite (the site uses SQLite, so no separate database server is needed)

## Local setup

1. Clone the repository and install dependencies:

```bash
   git clone https://github.com/DC321412/drupal-course-hub.git
   cd drupal-course-hub
   composer install
```

2. Install the site from the exported configuration:

```bash
   vendor/bin/drush site:install --existing-config --db-url=sqlite://sites/default/files/.ht.sqlite -y
```

3. Start the local server:

```bash
   cd web
   php -S localhost:8888 .ht.router.php
```

4. Open http://localhost:8888 and log in with the admin account created during installation.

Note: the database and uploaded files are not stored in Git. Course content and images must be re-created on a fresh install. Configuration (content types, fields, Views, roles, permissions, theme settings) is restored from `config/sync`.

## Configuration workflow

Export after making changes in the admin UI, then commit the result:

```bash
vendor/bin/drush cex -y
```

Import on another environment:

```bash
vendor/bin/drush cim -y
vendor/bin/drush cr
```

Check for differences at any time with `vendor/bin/drush config:status`.

## Deployment

On a server:

1. Pull the latest code.
2. Install dependencies without development packages:

```bash
   composer install --no-dev --optimize-autoloader
```

3. Apply database updates and configuration, then clear caches:

```bash
   vendor/bin/drush updb -y
   vendor/bin/drush cim -y
   vendor/bin/drush cr
```

Before going live: set a strong admin password, turn off Twig debug, keep `settings.local.php` and `development.services.yml` out of production, and use a proper production database instead of SQLite.

## Testing checklist

- Content types, taxonomy and `/courses` filters, sorting and pagination work
- `course_hub` page, form (valid and invalid input), block and access checks work
- Anonymous users cannot reach admin pages or unpublished content
- Layout works at 375px, 768px and 1280px widths
- Drupal log review: no errors (only Chrome DevTools probe 404s and temporary-file cleanup warnings)
- `drush config:status` reports no differences

## Project structure

- `web/modules/custom/course_hub`: custom module
- `web/themes/course_theme`: custom theme
- `config/sync`: exported site configuration