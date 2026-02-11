# Build and Release

**Tooling**
Build tasks are defined in `Gruntfile.js` and executed with `grunt`. Node dependencies are in `package.json`.

**Asset Outputs**
- CSS: `assets/css/unmark.css` from `assets/css/unmark.scss`.
- CSS: `assets/css/unmark_welcome.css` from `assets/css/unmark_welcome.scss`.
- JS bundles live in `assets/js/production/`.
- `unmark.plugins.js`.
- `unmark.loggedin.js`.
- `unmark.loggedout.js`.
- `unmark.bookmarklet.js`.

**Grunt Tasks**
1. `grunt` runs `sass:prod`, `concat:dev`, and `uglify:prod`.
1. `grunt dev` runs `sass:prod`, `concat:dev`, and `concat:custom` for easier debugging.
1. `grunt production` runs `makeCustom`, `concat:dev`, `concat:custom`, `sass:prod`, and `uglify:prod`.
1. `grunt release` builds a release in `release/unmark/`, zips it to `release/unmark.zip`, then cleans up.
1. `grunt makeCustom` cleans `custom/` and copies custom assets from `../unmark-internal/custom/` when present.

**Custom Grunt Overrides**
`custom/grunt_tasks/` can override or extend the base Grunt config. Tasks like `concat:custom` are expected to be defined there for custom builds.

**Watchers**
- `grunt watch` rebuilds JS on `assets/js/*.js` changes and SASS on `assets/css/*.scss` changes.

**Release Packaging**
The `grunt release` task copies application code, assets, and config templates into `release/unmark/` and zips them as `release/unmark.zip`.

**Update Notes**
Update this file when Grunt tasks, build outputs, or release packaging rules change.
