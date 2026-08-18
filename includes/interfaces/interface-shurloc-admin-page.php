<?php
/**
 * Admin page interface.
 *
 * @package ShurlocTools
 */

declare(strict_types=1);

namespace Shurloc\Tools;

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
