# ShurLoc Environment MU Plugin

The ShurLoc Environment MU plugin provides environment-specific safeguards that must run before normal WordPress plugins are loaded.

It uses a small, manually installed Must-Use plugin loader to load the environment implementation from the version-controlled **ShurLoc Tools** plugin.

## Files

The Must-Use plugin directory contains:

```text
wp-content/mu-plugins/
├── shurloc-environment-loader.php
└── README.md
```

The environment implementation is maintained in the ShurLoc Tools plugin:

```text
wp-content/plugins/shurloc-tools/
└── includes/
    └── environment/
        └── shurloc-environment-mu.php
```

## Architecture

`shurloc-environment-loader.php` is a minimal Must-Use plugin.

WordPress automatically loads PHP files located directly in `wp-content/mu-plugins/` before normal plugins are loaded.

The loader requires:

```text
wp-content/plugins/shurloc-tools/includes/environment/shurloc-environment-mu.php
```

This allows environment-specific functionality to execute early while keeping the implementation in the ShurLoc Tools plugin repository, where it can be version controlled, tested, and deployed with normal plugin releases.

The loader itself should remain small and stable.

## Installation

The loader must be installed manually.

1. Create the following directory if it does not already exist:

   ```text
   wp-content/mu-plugins/
   ```

2. Place `shurloc-environment-loader.php` in that directory.

3. Place this `README.md` file in the same directory for documentation.

No activation is required. WordPress automatically loads the loader on every request.

The loader will appear under **Plugins → Must-Use Plugins** in WordPress Admin.

## Environment Implementation

The environment implementation is located in the ShurLoc Tools plugin rather than directly in the MU-plugin directory.

The loader expects the implementation at:

```text
wp-content/plugins/shurloc-tools/includes/environment/shurloc-environment-mu.php
```

If that file does not exist, the loader does nothing.

Because the environment implementation executes before normal plugins are loaded, it must not assume that the normal ShurLoc Tools plugin bootstrap has already executed.

Any dependencies required by the environment implementation must either:

- already be available from WordPress at the MU-plugin loading stage; or
- be explicitly loaded by the environment implementation.

## Staging Safeguards

The environment implementation uses the WordPress environment type to determine whether staging-specific safeguards should be enabled.

The staging environment is identified by:

```php
wp_get_environment_type()
```

returning:

```text
staging
```

On SiteGround staging installations, `WP_ENVIRONMENT_TYPE` is configured as `staging`.

Production behavior must remain unaffected by staging-specific safeguards.

## Google Site Kit

The initial environment safeguard prevents Google Site Kit from operating on staging.

On staging, the environment implementation:

- prevents Google Site Kit from loading;
- disables automatic updates for Google Site Kit;
- annotates Google Site Kit in the WordPress Plugins screen to indicate that it has been disabled by the ShurLoc environment safeguards; and
- replaces its automatic-update control with an indication that automatic updates are disabled by ShurLoc Environment.

Site Kit remains installed and may remain marked as active in the database. The environment implementation filters it from WordPress's active plugin list before normal plugins are loaded.

On production, Site Kit behavior is unchanged.

## Updates

The MU loader is infrastructure and is intentionally **not automatically installed or updated by the ShurLoc Tools plugin**.

Changes to:

```text
shurloc-environment-loader.php
```

must be deployed manually.

Changes to the actual environment implementation:

```text
shurloc-tools/includes/environment/shurloc-environment-mu.php
```

are version controlled and deployed as part of the ShurLoc Tools plugin.

This separation keeps the early-loading mechanism simple and stable while allowing environment safeguards to evolve through the normal development and release process.

## Removal

To disable all ShurLoc MU environment safeguards, remove:

```text
wp-content/mu-plugins/shurloc-environment-loader.php
```

There is no WordPress activation or deactivation control for Must-Use plugins.

Removing the loader prevents the environment implementation from being loaded early. It does not modify the ShurLoc Tools plugin or any WordPress plugin activation settings.

## Important

Changes to MU-plugin infrastructure should be made carefully.

An error in a Must-Use plugin can affect WordPress before normal plugins are loaded and cannot be resolved through the normal plugin deactivation interface.

Keep the loader minimal, and test changes to the environment implementation on staging before deploying them to production.
