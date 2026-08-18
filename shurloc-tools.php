<?php
/**
 * Plugin Name:       Shur-loc Tools
 * Plugin URI:        https://shurloc.com/
 * Description:       Shared infrastructure for Shur-loc WordPress and WooCommerce tools.
 * Version:           0.3.0
 * Requires at least: 7.0
 * Requires PHP:      8.4
 * Author:            Shur-loc
 * Author URI:        https://shurloc.com/
 * Text Domain:       shurloc-tools
 *
 * @package ShurLocTools
 */

declare( strict_types=1 );

namespace Shurloc\Tools;

defined( 'ABSPATH' ) || exit;

/**
 * Plugin version.
 */
define(
	'SHURLOC_TOOLS_VERSION',
	'0.3.0'
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

/**
 * Initialize Shur-loc Tools.
 *
 * @return void
 */
function shurloc_tools_initialize(): void {

	require_once SHURLOC_TOOLS_PATH . 'includes/class-shurloc-admin-menu.php';
	require_once SHURLOC_TOOLS_PATH . 'includes/interfaces/interface-shurloc-admin-page.php';

	$admin_menu = new Shurloc_Admin_Menu();

	$admin_menu->register();
}

add_action(
	'plugins_loaded',
	__NAMESPACE__ . '\\shurloc_tools_initialize',
	10
);
