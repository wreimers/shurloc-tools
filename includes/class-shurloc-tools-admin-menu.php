<?php
/**
 * Shared ShurLoc Tools admin menu.
 *
 * Registers the ShurLoc Tools top-level menu, Overview submenu,
 * and shared overview rendering hook.
 *
 * @package ShurLocTools
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

/**
 * Registers the shared ShurLoc Tools admin menu.
 */
final class Shurloc_Tools_Admin_Menu {

	/**
	 * Parent menu slug.
	 *
	 * @var string
	 */
	public const MENU_SLUG = 'shurloc-tools';

	/**
	 * Required capability.
	 *
	 * @var string
	 */
	private const CAPABILITY = 'manage_options';

	/**
	 * Register WordPress hooks.
	 *
	 * @return void
	 */
	public function register(): void {

		add_action(
			'admin_menu',
			array(
				$this,
				'register_menu',
			),
			10
		);
	}

	/**
	 * Register the shared ShurLoc Tools admin menu.
	 *
	 * @return void
	 */
	public function register_menu(): void {

		add_menu_page(
			'ShurLoc Tools',
			'ShurLoc Tools',
			self::CAPABILITY,
			self::MENU_SLUG,
			array(
				$this,
				'render_overview_page',
			),
			'dashicons-admin-tools',
			56
		);

		$this->rename_overview_submenu();
	}

	/**
	 * Rename WordPress's automatically generated parent submenu.
	 *
	 * WordPress automatically adds the top-level page as the first submenu
	 * entry. Rename that entry to "Overview" while preserving its slug so
	 * clicking either the parent menu or Overview renders the same page.
	 *
	 * @return void
	 */
	private function rename_overview_submenu(): void {

		global $submenu;

		if (
			! isset( $submenu[ self::MENU_SLUG ] ) ||
			! is_array( $submenu[ self::MENU_SLUG ] )
		) {
			return;
		}

		foreach ( $submenu[ self::MENU_SLUG ] as &$submenu_item ) {

			if (
				isset( $submenu_item[2] ) &&
				self::MENU_SLUG === $submenu_item[2]
			) {
				$submenu_item[0] = 'Overview';
				break;
			}
		}

		unset( $submenu_item );
	}

	/**
	 * Render the ShurLoc Tools overview page.
	 *
	 * Individual ShurLoc plugins contribute their own overview sections
	 * through the shurloc_tools_overview action.
	 *
	 * @return void
	 */
	public function render_overview_page(): void {
		?>

		<div class="wrap">

			<h1>ShurLoc Tools</h1>

			<p>
				Administrative tools for ShurLoc WordPress and WooCommerce operations.
			</p>

			<?php
			/**
			 * Render ShurLoc Tools overview sections.
			 *
			 * Suggested priorities:
			 *
			 * 10 - Products
			 * 20 - Customers
			 * 30 - Checkout
			 */
			do_action( 'shurloc_tools_overview' );
			?>

		</div>

		<?php
	}
}
