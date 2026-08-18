<?php
/**
 * Plugin Name:       Shur-loc Environment Loader
 * Plugin URI:        https://shurloc.com/
 * Description:       Loads Shur-loc environment safeguards before normal plugins.
 * Version:           1.1.1
 * Author:            Shur-loc
 * Author URI:        https://shurloc.com/
 * Text Domain:       shurloc-environment-loader
 *
 * @package ShurlocTools
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

$environment_file = WP_PLUGIN_DIR
	. '/shurloc-tools/includes/environment/shurloc-environment-mu.php';

if ( file_exists( $environment_file ) ) {
	require_once $environment_file;

	if ( function_exists( 'shurloc_register_environment_hooks' ) ) {
		shurloc_register_environment_hooks();
	}
}
