# shurloc-tools

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
