<?php
/**
 * WordPress function test doubles.
 *
 * @package ShurlocTools
 */

declare( strict_types=1 );


if ( ! function_exists( 'add_action' ) ) {

	/**
	 * Register test action.
	 *
	 * @param string   $hook          Hook name.
	 * @param callable $callback      Callback.
	 * @param int      $priority      Priority.
	 * @param int      $accepted_args Accepted arguments.
	 * @return true
	 */
	function add_action(
		string $hook,
		$callback,
		int $priority = 10,
		int $accepted_args = 1
	): bool {

		$GLOBALS['shurloc_test_actions'][ $hook ][] = $callback;

		$GLOBALS['shurloc_test_action_metadata'][ $hook ][] = array(
			'priority'      => $priority,
			'accepted_args' => $accepted_args,
		);

		return true;
	}
}


if ( ! function_exists( 'add_filter' ) ) {

	/**
	 * Register test filter.
	 *
	 * @param string   $hook          Hook name.
	 * @param callable $callback      Callback.
	 * @param int      $priority      Priority.
	 * @param int      $accepted_args Accepted arguments.
	 * @return true
	 */
	function add_filter(
		string $hook,
		$callback,
		int $priority = 10,
		int $accepted_args = 1
	): bool {

		$GLOBALS['shurloc_test_filters'][ $hook ][] = $callback;

		$GLOBALS['shurloc_test_filter_metadata'][ $hook ][] = array(
			'priority'      => $priority,
			'accepted_args' => $accepted_args,
		);

		return true;
	}
}


if ( ! function_exists( 'add_menu_page' ) ) {

	/**
	 * Test replacement for add_menu_page().
	 *
	 * @param string         $page_title Page title.
	 * @param string         $menu_title Menu title.
	 * @param string         $capability Required capability.
	 * @param string         $menu_slug  Menu slug.
	 * @param callable|null  $callback   Page callback.
	 * @param string         $icon_url   Menu icon.
	 * @param int|float|null $position   Menu position.
	 * @return string
	 */
	function add_menu_page(
		string $page_title,
		string $menu_title,
		string $capability,
		string $menu_slug,
		?callable $callback = null,
		string $icon_url = '',
		$position = null
	): string {

		$GLOBALS['shurloc_test_menu_pages'][] = array(
			'page_title' => $page_title,
			'menu_title' => $menu_title,
			'capability' => $capability,
			'menu_slug'  => $menu_slug,
			'callback'   => $callback,
			'icon_url'   => $icon_url,
			'position'   => $position,
		);

		return 'toplevel_page_' . $menu_slug;
	}
}


if ( ! function_exists( 'do_action' ) ) {

	/**
	 * Execute test actions.
	 *
	 * Records the fired hook and executes callbacks registered through
	 * add_action(), respecting each callback's accepted argument count.
	 *
	 * @param string $hook    Hook name.
	 * @param mixed  ...$args Action arguments.
	 * @return void
	 */
	function do_action(
		string $hook,
		...$args
	): void {

		$GLOBALS['shurloc_test_fired_actions'][] = $hook;

		if ( empty( $GLOBALS['shurloc_test_actions'][ $hook ] ) ) {
			return;
		}

		foreach (
			$GLOBALS['shurloc_test_actions'][ $hook ]
			as $index => $callback
		) {
			$accepted_args =
				$GLOBALS['shurloc_test_action_metadata'][ $hook ][ $index ]['accepted_args']
				?? 1;

			$callback_args = array_slice(
				$args,
				0,
				$accepted_args
			);

			$callback( ...$callback_args );
		}
	}
}


if ( ! function_exists( 'add_submenu_page' ) ) {

	/**
	 * Test replacement for add_submenu_page().
	 *
	 * @param string         $parent_slug Parent menu slug.
	 * @param string         $page_title  Page title.
	 * @param string         $menu_title  Menu title.
	 * @param string         $capability  Required capability.
	 * @param string         $menu_slug   Menu slug.
	 * @param callable|null  $callback    Page callback.
	 * @param int|float|null $position    Menu position.
	 * @return string
	 */
	function add_submenu_page(
		string $parent_slug,
		string $page_title,
		string $menu_title,
		string $capability,
		string $menu_slug,
		?callable $callback = null,
		$position = null
	): string {

		$GLOBALS['shurloc_test_submenu_pages'][] = array(
			'parent_slug' => $parent_slug,
			'page_title'  => $page_title,
			'menu_title'  => $menu_title,
			'capability'  => $capability,
			'menu_slug'   => $menu_slug,
			'callback'    => $callback,
			'position'    => $position,
		);

		return $parent_slug . '_page_' . $menu_slug;
	}
}


if ( ! function_exists( 'wp_get_environment_type' ) ) {

	/**
	 * Get the test WordPress environment type.
	 *
	 * @return string
	 */
	function wp_get_environment_type(): string {

		return $GLOBALS['shurloc_test_environment_type'] ?? 'production';
	}
}


if ( ! function_exists( 'esc_html' ) ) {

	/**
	 * Escape test HTML text.
	 *
	 * @param string $text Text to escape.
	 * @return string
	 */
	function esc_html( string $text ): string {
		return $text;
	}
}
