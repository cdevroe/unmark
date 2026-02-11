# Codebase Map

**Architecture**
Unmark follows CodeIgniter's MVC layout. Requests enter `index.php`, get routed via `application/config/routes.php`, and are handled by controllers in `application/controllers/`, which call models in `application/models/` and render views in `application/views/`.

**Routing**
- Default controller is `Welcome` (`application/controllers/Welcome.php`).
- JSON and API-style routes are mapped in `application/config/routes.php` (for example `marks/*`, `labels/*`, `tags/*`, and `json/*`).
- Install and upgrade flows are handled by `Install` (`/install`, `/upgrade`).

**Controllers**
Key controllers live in `application/controllers/`:
- `Marks`, `Labels`, `Tags` handle core bookmark data and labels/tags.
- `Login`, `Logout`, `Register`, `User` handle auth and account changes.
- `Import`, `Export` handle backup and restore flows.
- `Json` serves JSON responses for the app and extensions.
- `Install`, `Migrations` manage setup and schema changes.

**Models**
Primary data models in `application/models/`:
- `Marks_model`, `Labels_model`, `Tags_model`.
- `Users_model`, `Users_to_marks_model`, `User_marks_to_tags_model`.
- `Tokens_model` for token management.

**Views**
HTML and email templates are in `application/views/` with subfolders like `layouts/`, `partials/`, `email/`, `marks/`, and `register/`.

**Configuration**
- Global config is in `application/config/`.
- Autoloaded dependencies are defined in `application/config/autoload.php`.
- App-level config values and error codes are in `application/config/all/app.php`.
- Database settings come from `application/config/database.php` (created from `database-sample.php`).

**Custom Overrides**
The `custom/` directory mirrors the CodeIgniter structure to override app behavior without editing core files. It also supports `custom/grunt_tasks/` to extend or override Grunt config.

**Frontend Assets**
- Source JS lives in `assets/js/`, SASS in `assets/css/`.
- Production bundles are output to `assets/js/production/` and `assets/css/`.
- PWA assets are in `manifest.json` and `service-worker.js`.

**Exports**
- `export-schema.md` documents the JSON export format used by Unmark.

**Update Notes**
Update this file when controllers, models, routes, or core directories change.
