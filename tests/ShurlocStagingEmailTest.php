<?php
/**
 * Tests for staging email redirection.
 *
 * @package ShurlocTools
 */

declare( strict_types=1 );

use PHPUnit\Framework\TestCase;

/**
 * Tests staging email redirection.
 */
final class ShurlocStagingEmailTest extends TestCase {

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
	 * Staging email hook is registered.
	 */
	public function test_staging_email_hook_is_registered(): void {
		shurloc_register_staging_email_hooks();

		$this->assertSame(
			array(
				'shurloc_redirect_staging_email',
			),
			$GLOBALS['shurloc_test_filters']['wp_mail']
		);

		$this->assertSame(
			array(
				array(
					'priority'      => 999,
					'accepted_args' => 1,
				),
			),
			$GLOBALS['shurloc_test_filter_metadata']['wp_mail']
		);
	}

	/**
	 * Production email is unchanged.
	 */
	public function test_production_email_is_unchanged(): void {
		$args = array(
			'to'      => 'customer@example.com',
			'subject' => 'Order received',
			'headers' => array(
				'Content-Type: text/html',
				'Cc: copy@example.com',
			),
		);

		$this->assertSame(
			$args,
			shurloc_redirect_staging_email( $args )
		);
	}

	/**
	 * Staging email recipient is redirected.
	 */
	public function test_staging_email_recipient_is_redirected(): void {
		$GLOBALS['shurloc_test_environment_type'] = 'staging';

		$args = array(
			'to'      => 'customer@example.com',
			'subject' => 'Order received',
		);

		$result = shurloc_redirect_staging_email( $args );

		$this->assertSame(
			SHURLOC_STAGING_EMAIL_RECIPIENT,
			$result['to']
		);
	}

	/**
	 * Staging email subject is prefixed.
	 */
	public function test_staging_email_subject_is_prefixed(): void {
		$GLOBALS['shurloc_test_environment_type'] = 'staging';

		$args = array(
			'to'      => 'customer@example.com',
			'subject' => 'Order received',
		);

		$result = shurloc_redirect_staging_email( $args );

		$this->assertSame(
			'[STAGING] Order received',
			$result['subject']
		);
	}

	/**
	 * Staging email without a subject remains without a subject.
	 */
	public function test_staging_email_without_subject_is_supported(): void {
		$GLOBALS['shurloc_test_environment_type'] = 'staging';

		$args = array(
			'to' => 'customer@example.com',
		);

		$result = shurloc_redirect_staging_email( $args );

		$this->assertArrayNotHasKey(
			'subject',
			$result
		);
	}

	/**
	 * Non-string staging email subject is unchanged.
	 */
	public function test_non_string_staging_email_subject_is_unchanged(): void {
		$GLOBALS['shurloc_test_environment_type'] = 'staging';

		$args = array(
			'to'      => 'customer@example.com',
			'subject' => null,
		);

		$result = shurloc_redirect_staging_email( $args );

		$this->assertNull( $result['subject'] );
	}

	/**
	 * CC and BCC headers are removed from array headers.
	 */
	public function test_recipient_headers_are_removed_from_array_headers(): void {
		$headers = array(
			'Content-Type: text/html',
			'Cc: copy@example.com',
			'Bcc: hidden@example.com',
			'Reply-To: sales@example.com',
		);

		$result = shurloc_remove_staging_email_recipient_headers(
			$headers
		);

		$this->assertSame(
			array(
				'Content-Type: text/html',
				'Reply-To: sales@example.com',
			),
			$result
		);
	}

	/**
	 * CC and BCC headers are removed from string headers.
	 */
	public function test_recipient_headers_are_removed_from_string_headers(): void {
		$headers = implode(
			"\r\n",
			array(
				'Content-Type: text/html',
				'Cc: copy@example.com',
				'Bcc: hidden@example.com',
				'Reply-To: sales@example.com',
			)
		);

		$result = shurloc_remove_staging_email_recipient_headers(
			$headers
		);

		$this->assertSame(
			implode(
				"\r\n",
				array(
					'Content-Type: text/html',
					'Reply-To: sales@example.com',
				)
			),
			$result
		);
	}

	/**
	 * Recipient header detection is case insensitive.
	 */
	public function test_recipient_header_detection_is_case_insensitive(): void {
		$this->assertTrue(
			shurloc_is_staging_email_recipient_header(
				'Cc: copy@example.com'
			)
		);

		$this->assertTrue(
			shurloc_is_staging_email_recipient_header(
				'BCC: hidden@example.com'
			)
		);

		$this->assertTrue(
			shurloc_is_staging_email_recipient_header(
				'  cc : copy@example.com'
			)
		);
	}

	/**
	 * Non-recipient headers are not identified as recipient headers.
	 */
	public function test_non_recipient_headers_are_not_recipient_headers(): void {
		$this->assertFalse(
			shurloc_is_staging_email_recipient_header(
				'Content-Type: text/html'
			)
		);

		$this->assertFalse(
			shurloc_is_staging_email_recipient_header(
				'Reply-To: sales@example.com'
			)
		);

		$this->assertFalse(
			shurloc_is_staging_email_recipient_header(
				'From: store@example.com'
			)
		);
	}

	/**
	 * Non-string array header values are preserved.
	 */
	public function test_non_string_array_header_values_are_preserved(): void {
		$headers = array(
			'Content-Type: text/html',
			123,
			null,
			'Cc: copy@example.com',
		);

		$result = shurloc_remove_staging_email_recipient_headers(
			$headers
		);

		$this->assertSame(
			array(
				'Content-Type: text/html',
				123,
				null,
			),
			$result
		);
	}

	/**
	 * Unexpected header value is returned unchanged.
	 */
	public function test_unexpected_header_value_is_unchanged(): void {
		$this->assertSame(
			123,
			shurloc_remove_staging_email_recipient_headers( 123 )
		);
	}
}
