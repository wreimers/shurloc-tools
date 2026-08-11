<?php
/**
 * Plugin Name:       Shur-loc Tools
 * Plugin URI:        https://shurloc.com/
 * Description:       Shared infrastructure for Shur-loc WordPress and WooCommerce tools.
 * Version:           0.1.0
 * Requires at least: 7.0
 * Requires PHP:      8.4
 * Author:            Shur-loc
 * Author URI:        https://shurloc.com/
 * Text Domain:       shurloc-tools
 *
 * @package ShurLocTools
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

/**
 * Plugin version.
 */
define(
	'SHURLOC_TOOLS_VERSION',
	'0.1.0'
);

/**
 * Main plugin file.
 */
define(
	'SHURLOC_TOOLS_FILE',
	__FILE__
);

/**
 * Plugin directory path.
 */
define(
	'SHURLOC_TOOLS_PATH',
	plugin_dir_path( __FILE__ )
);

/**
 * Plugin directory URL.
 */
define(
	'SHURLOC_TOOLS_URL',
	plugin_dir_url( __FILE__ )
);

require_once SHURLOC_TOOLS_PATH . 'includes/class-shurloc-tools-admin-menu.php';
require_once SHURLOC_TOOLS_PATH . 'includes/interfaces/interface-shurloc-admin-page.php';

/**
 * Initialize ShurLoc Tools.
 *
 * @return void
 */
function shurloc_tools_initialize(): void {

	$admin_menu = new Shurloc_Tools_Admin_Menu();

	$admin_menu->register();
}

shurloc_tools_initialize();
