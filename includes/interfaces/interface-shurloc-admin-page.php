<?php
/**
 * Admin page interface.
 *
 * @package ShurLocTools
 */

declare(strict_types=1);

/**
 * Represents an admin page that can be rendered.
 */
interface Shurloc_Admin_Page_Interface {

	/**
	 * Render the admin page.
	 *
	 * @return void
	 */
	public function render_page(): void;
}
