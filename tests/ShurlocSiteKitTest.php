<?php
/**
 * Tests for Google Site Kit staging safeguards.
 *
 * @package ShurlocEnvironment
 */

declare( strict_types=1 );

use PHPUnit\Framework\TestCase;

/**
 * Tests Google Site Kit staging safeguards.
 */
final class ShurlocSiteKitTest extends TestCase {

	/**
	 * Reset test state before each test.
	 */
	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['shurloc_test_environment_type'] = 'production';
		$GLOBALS['shurloc_test_filters']          = array();
		$GLOBALS['shurloc_test_filter_metadata']  = array();
	}

	/**
	 * Clean up test state after each test.
	 */
	protected function tearDown(): void {
		unset(
			$GLOBALS['shurloc_test_environment_type'],
			$GLOBALS['shurloc_test_filters'],
			$GLOBALS['shurloc_test_filter_metadata']
		);

		parent::tearDown();
	}

	/**
	 * Site Kit remains active in production.
	 */
	public function test_site_kit_remains_active_in_production(): void {
		$plugins = array(
			'akismet/akismet.php',
			SHURLOC_SITE_KIT_PLUGIN,
		);

		$this->assertSame(
			$plugins,
			shurloc_disable_site_kit_on_staging( $plugins )
		);
	}

	/**
	 * Site Kit is removed from active plugins on staging.
	 */
	public function test_site_kit_is_removed_on_staging(): void {
		$GLOBALS['shurloc_test_environment_type'] = 'staging';

		$plugins = array(
			'akismet/akismet.php',
			SHURLOC_SITE_KIT_PLUGIN,
			'woocommerce/woocommerce.php',
		);

		$this->assertSame(
			array(
				'akismet/akismet.php',
				'woocommerce/woocommerce.php',
			),
			shurloc_disable_site_kit_on_staging( $plugins )
		);
	}

	/**
	 * Active plugin indexes are normalized after removing Site Kit.
	 */
	public function test_active_plugin_indexes_are_normalized(): void {
		$GLOBALS['shurloc_test_environment_type'] = 'staging';

		$plugins = array(
			0 => 'akismet/akismet.php',
			1 => SHURLOC_SITE_KIT_PLUGIN,
			2 => 'woocommerce/woocommerce.php',
		);

		$result = shurloc_disable_site_kit_on_staging( $plugins );

		$this->assertSame(
			array(
				0 => 'akismet/akismet.php',
				1 => 'woocommerce/woocommerce.php',
			),
			$result
		);
	}

	/**
	 * Site Kit automatic updates are unchanged in production.
	 */
	public function test_site_kit_auto_update_is_unchanged_in_production(): void {
		$item = (object) array(
			'plugin' => SHURLOC_SITE_KIT_PLUGIN,
		);

		$this->assertTrue(
			shurloc_disable_site_kit_auto_updates_on_staging(
				true,
				$item
			)
		);

		$this->assertNull(
			shurloc_disable_site_kit_auto_updates_on_staging(
				null,
				$item
			)
		);
	}

	/**
	 * Site Kit automatic updates are disabled on staging.
	 */
	public function test_site_kit_auto_update_is_disabled_on_staging(): void {
		$GLOBALS['shurloc_test_environment_type'] = 'staging';

		$item = (object) array(
			'plugin' => SHURLOC_SITE_KIT_PLUGIN,
		);

		$this->assertFalse(
			shurloc_disable_site_kit_auto_updates_on_staging(
				true,
				$item
			)
		);
	}

	/**
	 * Automatic updates for other plugins are unchanged on staging.
	 */
	public function test_other_plugin_auto_update_is_unchanged_on_staging(): void {
		$GLOBALS['shurloc_test_environment_type'] = 'staging';

		$item = (object) array(
			'plugin' => 'woocommerce/woocommerce.php',
		);

		$this->assertTrue(
			shurloc_disable_site_kit_auto_updates_on_staging(
				true,
				$item
			)
		);
	}

	/**
	 * Auto-update value is unchanged when plugin data is missing.
	 */
	public function test_auto_update_is_unchanged_when_plugin_property_is_missing(): void {
		$GLOBALS['shurloc_test_environment_type'] = 'staging';

		$item = new stdClass();

		$this->assertTrue(
			shurloc_disable_site_kit_auto_updates_on_staging(
				true,
				$item
			)
		);
	}

	/**
	 * Site Kit action links are unchanged in production.
	 */
	public function test_site_kit_action_links_are_unchanged_in_production(): void {
		$actions = array(
			'deactivate' => 'Deactivate',
			'settings'   => 'Settings',
		);

		$this->assertSame(
			$actions,
			shurloc_site_kit_staging_plugin_actions(
				$actions,
				SHURLOC_SITE_KIT_PLUGIN,
				array(),
				'all'
			)
		);
	}

	/**
	 * Site Kit action links are replaced on staging.
	 */
	public function test_site_kit_action_links_are_replaced_on_staging(): void {
		$GLOBALS['shurloc_test_environment_type'] = 'staging';

		$actions = array(
			'deactivate' => 'Deactivate',
			'settings'   => 'Settings',
		);

		$this->assertSame(
			array(
				'shurloc_environment' =>
					'Disabled on staging by ShurLoc Environment',
			),
			shurloc_site_kit_staging_plugin_actions(
				$actions,
				SHURLOC_SITE_KIT_PLUGIN,
				array(),
				'all'
			)
		);
	}

	/**
	 * Other plugin action links are unchanged on staging.
	 */
	public function test_other_plugin_action_links_are_unchanged_on_staging(): void {
		$GLOBALS['shurloc_test_environment_type'] = 'staging';

		$actions = array(
			'deactivate' => 'Deactivate',
		);

		$this->assertSame(
			$actions,
			shurloc_site_kit_staging_plugin_actions(
				$actions,
				'woocommerce/woocommerce.php',
				array(),
				'all'
			)
		);
	}

	/**
	 * Site Kit automatic-update setting is unchanged in production.
	 */
	public function test_site_kit_auto_update_setting_is_unchanged_in_production(): void {
		$html = '<span>Enable auto-updates</span>';

		$this->assertSame(
			$html,
			shurloc_site_kit_staging_auto_update_setting(
				$html,
				SHURLOC_SITE_KIT_PLUGIN
			)
		);
	}

	/**
	 * Site Kit automatic-update setting is replaced on staging.
	 */
	public function test_site_kit_auto_update_setting_is_replaced_on_staging(): void {
		$GLOBALS['shurloc_test_environment_type'] = 'staging';

		$this->assertSame(
			'Auto-updates disabled by ShurLoc Environment',
			shurloc_site_kit_staging_auto_update_setting(
				'<span>Enable auto-updates</span>',
				SHURLOC_SITE_KIT_PLUGIN
			)
		);
	}

	/**
	 * Other plugin automatic-update setting is unchanged on staging.
	 */
	public function test_other_plugin_auto_update_setting_is_unchanged_on_staging(): void {
		$GLOBALS['shurloc_test_environment_type'] = 'staging';

		$html = '<span>Enable auto-updates</span>';

		$this->assertSame(
			$html,
			shurloc_site_kit_staging_auto_update_setting(
				$html,
				'woocommerce/woocommerce.php'
			)
		);
	}

	/**
	 * Site Kit hooks are registered.
	 */
	public function test_site_kit_hooks_are_registered(): void {
		shurloc_register_site_kit_hooks();

		$this->assertSame(
			array(
				'shurloc_disable_site_kit_on_staging',
			),
			$GLOBALS['shurloc_test_filters']['option_active_plugins']
		);

		$this->assertSame(
			array(
				array(
					'priority'      => 10,
					'accepted_args' => 1,
				),
			),
			$GLOBALS['shurloc_test_filter_metadata']['option_active_plugins']
		);

		$this->assertSame(
			array(
				'shurloc_disable_site_kit_auto_updates_on_staging',
			),
			$GLOBALS['shurloc_test_filters']['auto_update_plugin']
		);

		$this->assertSame(
			array(
				array(
					'priority'      => 10,
					'accepted_args' => 2,
				),
			),
			$GLOBALS['shurloc_test_filter_metadata']['auto_update_plugin']
		);

		$this->assertSame(
			array(
				'shurloc_site_kit_staging_plugin_actions',
			),
			$GLOBALS['shurloc_test_filters']['plugin_action_links']
		);

		$this->assertSame(
			array(
				array(
					'priority'      => 10,
					'accepted_args' => 4,
				),
			),
			$GLOBALS['shurloc_test_filter_metadata']['plugin_action_links']
		);

		$this->assertSame(
			array(
				'shurloc_site_kit_staging_auto_update_setting',
			),
			$GLOBALS['shurloc_test_filters']['plugin_auto_update_setting_html']
		);

		$this->assertSame(
			array(
				array(
					'priority'      => 10,
					'accepted_args' => 2,
				),
			),
			$GLOBALS['shurloc_test_filter_metadata']['plugin_auto_update_setting_html']
		);
	}
}
