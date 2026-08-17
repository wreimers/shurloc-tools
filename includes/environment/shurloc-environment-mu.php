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
 * Site Kit remains installed, but is filtered from the active plugin list
 * before normal plugins are loaded.
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
 * @param bool|null $update Whether the plugin should be automatically updated.
 * @param object    $item   Plugin update data.
 * @return bool|null
 */
function shurloc_disable_site_kit_auto_updates_on_staging(
	?bool $update,
	object $item
): ?bool {
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
 * Replace Google Site Kit action links with a staging-disabled message.
 *
 * @param string[]            $actions     Plugin action links.
 * @param string              $plugin_file Plugin basename.
 * @param array<string,mixed> $plugin_data Plugin data.
 * @param string              $context     Plugin list context.
 * @return string[]
 */
function shurloc_site_kit_staging_plugin_actions(
	array $actions,
	string $plugin_file,
	array $plugin_data,
	string $context
): array {
	if ( ! shurloc_is_staging_environment() ) {
		return $actions;
	}

	if ( SHURLOC_SITE_KIT_PLUGIN !== $plugin_file ) {
		return $actions;
	}

	unset( $plugin_data );
	unset( $context );

	return array(
		'shurloc_environment' => sprintf(
			'%s',
			esc_html(
				'Disabled on staging by ShurLoc Environment'
			)
		),
	);
}
add_filter(
	'plugin_action_links',
	'shurloc_site_kit_staging_plugin_actions',
	10,
	4
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
		'%s',
		esc_html(
			'Auto-updates disabled by ShurLoc Environment'
		)
	);
}
add_filter(
	'plugin_auto_update_setting_html',
	'shurloc_site_kit_staging_auto_update_setting',
	10,
	2
);
