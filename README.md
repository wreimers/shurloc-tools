# Shur-loc Tools

Shared infrastructure and administration functionality for Shur-loc® WordPress and WooCommerce plugins.

## Features

- Provide shared infrastructure for Shur-loc plugins.
- Provide the top-level **Shur-loc Tools** WordPress admin menu.
- Define shared interfaces used by Shur-loc plugin administration pages.
- Provide common functionality used across Shur-loc plugins.
- Manage environment-specific safeguards for staging and production environments.
- Disable Google Site Kit on staging environments.
- Disable automatic Google Site Kit updates on staging environments.
- Redirect outgoing staging email to a designated test address.
- Clearly identify redirected staging email while preserving information about the original recipients.

## Requirements

- WordPress 7.0 or later
- PHP 8.4 or later

## Installation

1. Install and activate **Shur-loc Tools**.
2. Install and activate the Shur-loc plugins that depend on it.
3. Navigate to **Shur-loc Tools** in the WordPress admin to access the available plugin tools.

Shur-loc Tools provides shared infrastructure and may not expose significant functionality on its own. Additional administration pages are registered by dependent plugins such as **Shur-loc Product Tools**, **Shur-loc Customer Tools**, and **Shur-loc Checkout Tools**.

## Environment Loader

Environment safeguards are loaded through the Shur-loc Environment Loader must-use plugin so they can run before normal WordPress plugins.

The MU plugin loader is installed at:

```text
wp-content/
├── mu-plugins/
│   └── shurloc-environment-loader.php
└── plugins/
    └── shurloc-tools/
```

The loader loads the environment functionality provided by Shur-loc Tools and registers its environment-specific hooks.

On staging environments, these safeguards include:

- Preventing Google Site Kit from loading.
- Preventing automatic updates of Google Site Kit.
- Replacing Site Kit plugin action links with a staging-environment notice.
- Redirecting outgoing WordPress email to the configured staging mailbox.
- Removing CC and BCC recipients from redirected staging email.
- Annotating redirected messages so their staging origin and original recipients can be identified.

Environment-specific behavior is determined using the WordPress environment type.

## Development

Install the development dependencies with Composer:

```bash
composer install
```

### PHPUnit

The project includes PHPUnit unit tests covering shared infrastructure, administration functionality, environment detection, staging email safeguards, Site Kit safeguards, and other plugin behavior.

Run the test suite:

```bash
composer test
```

### PHP_CodeSniffer

PHP_CodeSniffer is used to enforce the project's PHP coding standards.

Run code style checks:

```bash
composer lint
```

### PHPStan

PHPStan is used for static analysis of the plugin source and test suite.

Run static analysis:

```bash
composer phpstan
```

### Dependent Plugin Development

Other Shur-loc plugins depend on Shur-loc Tools for shared infrastructure and interfaces.

For development, the repositories should be checked out as sibling directories:

```text
wordpress-plugins/
├── shurloc-tools/
├── shurloc-product-tools/
├── shurloc-customer-tools/
└── shurloc-checkout-tools/
```

This layout allows development and static-analysis tooling in dependent plugins to resolve classes and interfaces provided by `shurloc-tools`.

### Release Packages

A PowerShell build script is provided for creating distributable plugin packages:

```powershell
.\bin\build.ps1
```

Development files, tests, static-analysis configuration, and other files not required at runtime are excluded from release packages.

## License

This project is licensed under the MIT License. See the `LICENSE` file for details.
