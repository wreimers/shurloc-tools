<?php
/**
 * Plugin Name:       ShurLoc Environment Loader
 * Plugin URI:        https://shurloc.com/
 * Description:       Loads ShurLoc environment safeguards before normal plugins.
 * Version:           1.0.0
 * Author:            ShurLoc
 * Author URI:        https://shurloc.com/
 * Text Domain:       shurloc-environment-loader
 *
 * @package ShurlocEnvironmentLoader
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

$environment_file = WP_PLUGIN_DIR
	. '/shurloc-tools/includes/environment/shurloc-environment-mu.php';

if ( file_exists( $environment_file ) ) {
	require_once $environment_file;
}
