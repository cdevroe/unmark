# Codex Guide: Unmark

**Overview**
Unmark is a PHP web app built on CodeIgniter (see `system/` and `application/`). The web entry point is `index.php`. Local dev is typically via Docker Compose, with Apache + PHP 7.4 and MySQL 8.0.

**Quick Start**
1. Copy `application/config/database-sample.php` to `application/config/database.php` and fill in credentials.
1. Run `docker-compose up -d`.
1. Open `http://localhost:8080` and complete the install flow.
1. Run `npm install` and then `grunt` to build front-end assets.

**Build Tasks**
1. `grunt` runs SASS compilation, JS concat (dev), and JS minification.
1. `grunt dev` builds unminified JS for debugging.
1. `grunt release` prepares `release/unmark.zip`.

**Key Paths**
- `application/` CodeIgniter app code (controllers, models, views, config, migrations).
- `system/` CodeIgniter core.
- `assets/` JS, SASS, images, and production bundles.
- `custom/` Optional overrides for app code and grunt tasks.
- `bookmarklets/` Bookmarklet scripts.
- `export-schema.md` JSON export schema documentation.

**Update Notes**
Keep this file and the docs in `docs/` updated when you add routes, controllers, build steps, or major file structure changes.
