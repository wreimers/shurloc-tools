<?php
/**
 * Tests for ShurLoc Environment shared functionality.
 *
 * @package ShurlocTools
 */

declare( strict_types=1 );

use PHPUnit\Framework\TestCase;

/**
 * Tests shared ShurLoc Environment functionality.
 */
final class ShurlocEnvironmentTest extends TestCase {

	/**
	 * Reset test state before each test.
	 */
	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['shurloc_test_environment_type'] = 'production';
	}

	/**
	 * Clean up test state after each test.
	 */
	protected function tearDown(): void {
		unset( $GLOBALS['shurloc_test_environment_type'] );

		parent::tearDown();
	}

	/**
	 * Staging environment is detected.
	 */
	public function test_staging_environment_is_detected(): void {
		$GLOBALS['shurloc_test_environment_type'] = 'staging';

		$this->assertTrue(
			shurloc_is_staging_environment()
		);
	}

	/**
	 * Production environment is not detected as staging.
	 */
	public function test_production_environment_is_not_staging(): void {
		$GLOBALS['shurloc_test_environment_type'] = 'production';

		$this->assertFalse(
			shurloc_is_staging_environment()
		);
	}

	/**
	 * Development environment is not detected as staging.
	 */
	public function test_development_environment_is_not_staging(): void {
		$GLOBALS['shurloc_test_environment_type'] = 'development';

		$this->assertFalse(
			shurloc_is_staging_environment()
		);
	}

	/**
	 * Local environment is not detected as staging.
	 */
	public function test_local_environment_is_not_staging(): void {
		$GLOBALS['shurloc_test_environment_type'] = 'local';

		$this->assertFalse(
			shurloc_is_staging_environment()
		);
	}
}
