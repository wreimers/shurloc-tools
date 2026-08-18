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
 * @package ShurlocTools
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

/**
 * Google Site Kit plugin basename.
 */
const SHURLOC_SITE_KIT_PLUGIN = 'google-site-kit/google-site-kit.php';

/**
 * Staging email recipient.
 */
const SHURLOC_STAGING_EMAIL_RECIPIENT = 'liam@shurloc.com';

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

/**
 * Redirect all outgoing WordPress email on staging.
 *
 * @param array<string,mixed> $args wp_mail() arguments.
 * @return array<string,mixed>
 */
function shurloc_redirect_staging_email( array $args ): array {
	if ( ! shurloc_is_staging_environment() ) {
		return $args;
	}

	$args['to'] = SHURLOC_STAGING_EMAIL_RECIPIENT;

	if ( isset( $args['headers'] ) ) {
		$args['headers'] = shurloc_remove_staging_email_recipient_headers(
			$args['headers']
		);
	}

	if (
		isset( $args['subject'] ) &&
		is_string( $args['subject'] )
	) {
		$args['subject'] = '[STAGING] ' . $args['subject'];
	}

	return $args;
}

/**
 * Remove CC and BCC headers from an outgoing email.
 *
 * @param mixed $headers Email headers.
 * @return mixed
 */
function shurloc_remove_staging_email_recipient_headers(
	mixed $headers
): mixed {
	if ( is_array( $headers ) ) {
		return array_values(
			array_filter(
				$headers,
				static function ( mixed $header ): bool {
					if ( ! is_string( $header ) ) {
						return true;
					}

					return ! shurloc_is_staging_email_recipient_header(
						$header
					);
				}
			)
		);
	}

	if ( ! is_string( $headers ) ) {
		return $headers;
	}

	$header_lines = preg_split(
		'/\r\n|\r|\n/',
		$headers
	);

	if ( false === $header_lines ) {
		return $headers;
	}

	$header_lines = array_filter(
		$header_lines,
		static function ( string $header ): bool {
			return ! shurloc_is_staging_email_recipient_header(
				$header
			);
		}
	);

	return implode( "\r\n", $header_lines );
}

/**
 * Determine whether an email header contains an additional recipient.
 *
 * @param string $header Email header.
 * @return bool
 */
function shurloc_is_staging_email_recipient_header(
	string $header
): bool {
	return 1 === preg_match(
		'/^(cc|bcc)\s*:/i',
		trim( $header )
	);
}

/**
 * Register Google Site Kit staging safeguard hooks.
 *
 * @return void
 */
function shurloc_register_site_kit_hooks(): void {
	add_filter(
		'option_active_plugins',
		'shurloc_disable_site_kit_on_staging'
	);

	add_filter(
		'auto_update_plugin',
		'shurloc_disable_site_kit_auto_updates_on_staging',
		10,
		2
	);

	add_filter(
		'plugin_action_links',
		'shurloc_site_kit_staging_plugin_actions',
		10,
		4
	);

	add_filter(
		'plugin_auto_update_setting_html',
		'shurloc_site_kit_staging_auto_update_setting',
		10,
		2
	);
}

/**
 * Register staging email hooks.
 *
 * @return void
 */
function shurloc_register_staging_email_hooks(): void {
	add_filter(
		'wp_mail',
		'shurloc_redirect_staging_email',
		999
	);
}

/**
 * Register all ShurLoc Environment hooks.
 *
 * @return void
 */
function shurloc_register_environment_hooks(): void {
	shurloc_register_site_kit_hooks();
	shurloc_register_staging_email_hooks();
}
