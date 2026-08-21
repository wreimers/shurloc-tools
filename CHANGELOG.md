# Changelog

## [0.3.2] - 2026-08-21

### Internal Improvements

- Fixed branding in several files.
- Updated README.
- Added WooCommerce stubs to VS Code Intelephense configuration.

## [0.3.1] - 2026-08-18

### Changed

- Updated branding - changed ShurLoc to Shur-loc.

## [0.3.0] - 2026-08-18

### Added
- Added staging email redirection to prevent outgoing WordPress and WooCommerce email from reaching production recipients.
- Added staging subject prefixes and removal of CC/BCC recipients from redirected email.
- Added automated tests for environment detection, Google Site Kit safeguards, and staging email behavior.

### Changed
- Refactored environment hook registration into explicit registration functions for improved testability.
- Updated the environment loader to register safeguards after loading the environment module.

## [0.2.0] - 2026-08-17

### Added

- Added environment-specific safeguards for staging environments.
- Added Must-Use plugin loader for loading environment safeguards before normal WordPress plugins.
- Added automatic Google Site Kit suppression on staging.
- Added staging-specific Site Kit status messaging in the WordPress Plugins screen.
- Added locked automatic-update status for Site Kit on staging.

### Changed

- Google Site Kit is now prevented from loading on staging while remaining unaffected on production.
- Google Site Kit automatic updates are now disabled on staging.
- Site Kit activation and deletion actions are replaced with an environment-disabled message on staging.

## [0.1.3] - 2026-08-12

### Changed

- Namespaced plugin to Shurloc\Tools
- renamed `Shurloc_Tools_Admin_Menu` to `Shurloc_Admin_Menu`
- Moved plugin initialization into bootstrap function called with `add_action`

## [0.1.2] - 2026-08-12

### Changed

- Extracted Shurloc_Admin_Page_Interface fro `shurloc-product-tools` into this plugin.
- Excluded PHPStan files from build.

## [0.1.1] - 2026-08-09

### Added

- Added line ending default to VS Code workspace settings.

### Testing

- Added PHPStan and Composer script `check`.

## [0.1.0] - 2026-08-07

### Added
- Initial plugin files
- Configuration files
- WordPress Coding Standards configuration
- Build script
