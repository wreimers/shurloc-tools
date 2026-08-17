<?php
/**
 * Plugin Name: ShurLoc Environment
 * Plugin URI:  https://shurloc.com/
 * Description: Environment-specific safeguards for the ShurLoc website.
 * Version:     1.0.0
 * Author:      ShurLoc
 * Author URI:  https://shurloc.com/
 * Text Domain: shurloc-environment
 *
 * @package ShurlocEnvironment
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

/**
 * Google Site Kit plugin basename.
 */
const SHURLOC_SITE_KIT_PLUGIN = 'google-site-kit/google-site-kit.php';

/**
 * Determine whether the current environment is staging.
 *
 * @return bool
 */
function shurloc_is_staging_environment(): bool {
	return 'staging' === wp_get_environment_type();
}

/**
 * Prevent Google Site Kit from loading on staging.
 *
 * Site Kit remains marked active in the database, but is filtered from the
 * active plugin list before normal plugins are loaded.
 *
 * @param string[] $plugins Active plugin basenames.
 * @return string[]
 */
function shurloc_disable_site_kit_on_staging( array $plugins ): array {
	if ( ! shurloc_is_staging_environment() ) {
		return $plugins;
	}

	return array_values(
		array_diff(
			$plugins,
			array( SHURLOC_SITE_KIT_PLUGIN )
		)
	);
}
add_filter(
	'option_active_plugins',
	'shurloc_disable_site_kit_on_staging'
);

/**
 * Disable automatic updates for Google Site Kit on staging.
 *
 * @param bool   $update Whether the plugin should be automatically updated.
 * @param object $item   Plugin update data.
 * @return bool
 */
function shurloc_disable_site_kit_auto_updates_on_staging(
	bool $update,
	object $item
): bool {
	if ( ! shurloc_is_staging_environment() ) {
		return $update;
	}

	if (
		isset( $item->plugin ) &&
		SHURLOC_SITE_KIT_PLUGIN === $item->plugin
	) {
		return false;
	}

	return $update;
}
add_filter(
	'auto_update_plugin',
	'shurloc_disable_site_kit_auto_updates_on_staging',
	10,
	2
);

/**
 * Add a staging-disabled notice to Google Site Kit in the Plugins list.
 *
 * @param string[] $plugin_meta Plugin metadata.
 * @param string   $plugin_file Plugin basename.
 * @return string[]
 */
function shurloc_site_kit_staging_plugin_meta(
	array $plugin_meta,
	string $plugin_file
): array {
	if ( ! shurloc_is_staging_environment() ) {
		return $plugin_meta;
	}

	if ( SHURLOC_SITE_KIT_PLUGIN !== $plugin_file ) {
		return $plugin_meta;
	}

	$plugin_meta[] = sprintf(
		'<strong>%s</strong>',
		esc_html(
			'Disabled on staging by ShurLoc Environment'
		)
	);

	return $plugin_meta;
}
add_filter(
	'plugin_row_meta',
	'shurloc_site_kit_staging_plugin_meta',
	10,
	2
);

/**
 * Replace the Google Site Kit automatic-update control on staging.
 *
 * @param string $html        Automatic-update setting HTML.
 * @param string $plugin_file Plugin basename.
 * @return string
 */
function shurloc_site_kit_staging_auto_update_setting(
	string $html,
	string $plugin_file
): string {
	if ( ! shurloc_is_staging_environment() ) {
		return $html;
	}

	if ( SHURLOC_SITE_KIT_PLUGIN !== $plugin_file ) {
		return $html;
	}

	return sprintf(
		'<span class="dashicons dashicons-lock" aria-hidden="true"></span> %s',
		esc_html(
			'Disabled by ShurLoc Environment'
		)
	);
}
add_filter(
	'plugin_auto_update_setting_html',
	'shurloc_site_kit_staging_auto_update_setting',
	10,
	2
);
