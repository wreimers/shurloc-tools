<?php
/**
 * PHPUnit bootstrap.
 *
 * @package ShurLocProductTools
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __DIR__ ) . DIRECTORY_SEPARATOR );
}

// Load Composer's autoloader.
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

// Load WordPress function doubles.
require_once dirname( __DIR__ ) . '/tests/wordpress-functions.php';

// Load menu.
require_once dirname( __DIR__ ) . '/includes/class-shurloc-tools-admin-menu.php';
