<?php
/**
 * Plugin Name: ShurLoc Tools
 * Description: Shared infrastructure for ShurLoc WordPress and WooCommerce tools.
 * Version: 0.1.0
 * Author: ShurLoc
 * Text Domain: shurloc-tools
 *
 * @package ShurLocTools
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

/**
 * Plugin version.
 *
 * @var string
 */
define(
	'SHURLOC_TOOLS_VERSION',
	'0.1.0'
);

/**
 * Main plugin file.
 *
 * @var string
 */
define(
	'SHURLOC_TOOLS_FILE',
	__FILE__
);

/**
 * Plugin directory path.
 *
 * @var string
 */
define(
	'SHURLOC_TOOLS_PATH',
	plugin_dir_path( __FILE__ )
);

/**
 * Plugin directory URL.
 *
 * @var string
 */
define(
	'SHURLOC_TOOLS_URL',
	plugin_dir_url( __FILE__ )
);

require_once SHURLOC_TOOLS_PATH
	. 'includes/class-shurloc-tools-admin-menu.php';

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
