# Day 4 Notes: Theming, Configuration and Performance

## Theme: course_theme

- Location: web/themes/course_theme (standalone theme, base theme stable9)
- Twig overrides: templates/node/node--course--full.html.twig (full page) and
  templates/node/node--course--teaser--featured.html.twig (featured card)
- course_theme.theme: course_theme_preprocess_node() adds reading_time and
  is_featured; course_theme_theme_suggestions_node_alter() adds the
  node__course__teaser__featured suggestion
- Libraries (course_theme.libraries.yml): global (site CSS and JS, loaded on
  every page from course_theme.info.yml) and course-page (attached from the
  course templates with attach_library)
- JS: js/course-theme.js uses Drupal.behaviors and once() for a back-to-top button
- Responsive: mobile-first CSS, a grid with media queries on the course page,
  and flex-wrapping menus

## Configuration management

- Config lives in config/sync (setting in settings.php, which is not committed):
  $settings['config_sync_directory'] = '../config/sync';
- Export: drush config:export (cex). Import: drush config:import (cim).
  Compare: drush config:status.
- Check: change a value with drush config:set, run drush cim, and the file
  version replaces the database value.
- Importing on another site needs the same site UUID, or a fresh install from
  this config.

## Caching

- Layers: Internal Page Cache (anonymous pages), Dynamic Page Cache (pages
  with personalized parts), render cache (blocks, nodes).
- Cache metadata on render arrays: tags (invalidate when something changes,
  for example node_list:course), contexts (vary by user, permissions, URL),
  max-age (time limit).
- Settings: /admin/config/development/performance (aggregate CSS and JS).
- Development only: settings.local.php and development.services.yml turn off
  render caching and Twig caching. Both files are git-ignored.
- After any code, template or config change: drush cr.

## Security

- Keep core and modules updated: composer audit, composer update "drupal/*"
  --with-all-dependencies, then drush updb.
- Never commit settings.php, settings.local.php or database files with secrets.
- Lock settings after editing: chmod 555 web/sites/default and
  chmod 444 web/sites/default/settings.php.
- Twig escapes output automatically. Avoid the raw filter on untrusted content.
- Give roles only the permissions they need. Never grant anonymous users
  administer permissions.
- Set trusted_host_patterns before going to production.

## Debugging

- Twig debug (development.services.yml): shows THEME DEBUG comments with
  template suggestions and the file in use.
- Logs: drush watchdog:show (ws) and drush ws --tail, or /admin/reports/dblog.
- Status report: /admin/reports/status.
- Verbose errors locally: $config['system.logging']['error_level'] = 'verbose';
  in settings.local.php.
- Check a service parameter: drush php:eval with getParameter().

## Command cheat sheet

Composer:

    composer require drush/drush
    composer require drupal/module_name
    composer update "drupal/*" --with-all-dependencies
    composer audit
    composer show drupal/core

Drush:

    drush cr                         rebuild caches
    drush theme:install course_theme install a theme
    drush en module -y / drush pmu module -y
    drush cex -y / drush cim -y      config export / import
    drush config:set / config:get    change or read one value
    drush updb -y                    run database updates
    drush uli                        one-time login link
    drush ws --tail                  watch the log

Typical deploy order: composer install, drush updb -y, drush cim -y, drush cr.
